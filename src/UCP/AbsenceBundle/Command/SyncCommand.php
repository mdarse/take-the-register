<?php

namespace UCP\AbsenceBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Guzzle\Http\Client;
use UCP\AbsenceBundle\Entity\KVObject;
use UCP\AbsenceBundle\Entity\Lesson;

class SyncCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('ucp:lesson:sync')
            ->setDescription('Sync upcomming lessons with Google calendar')
            // ->addArgument(
            //     'name',
            //     InputArgument::OPTIONAL,
            //     'Who do you want to greet?'
            // )
            // ->addOption(
            //    'yell',
            //    null,
            //    InputOption::VALUE_NONE,
            //    'If set, the task will yell in uppercase letters'
            // )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Check app status
        $accessToken = $this->getValue('sync.access_token');
        if (!$accessToken) {
            throw new \Exception("No Google account linked.");
        }
        $calendarId = $this->getValue('sync.calendar_id');
        $calendarName = $this->getValue('sync.calendar_name');
        if (!$calendarId || !$calendarName) {
            throw new \Exception("No Google calendar linked.");
        }
        $output->writeln(sprintf('Starting sync on calendar %s.', $calendarName));

        // Start sync
        $client = new Client('https://www.googleapis.com/calendar/v3/');
        $request = $client->get(array(
            'calendars/{calendarId}/events?'.
            // 'showDeleted={showDeleted}&'.
            'singleEvents={singleEvents}&'.
            'orderBy={orderBy}&'.
            // 'maxResults={maxResults}&'.
            'timeMin={timeMin}&',
            // 'timeMax={timeMax}&',
            // 'updatedMin={updatedMin}&',
            array(
                'calendarId' => $calendarId,
                'showDeleted' => 'true',
                'singleEvents' => 'true',
                // Events order. The default is an unspecified, stable order.
                'orderBy' => 'startTime', // "startTime" require "singleEvents" to true
                'maxResults' => 20,
                // Upper bound (exclusive) for an event's start time to filter by.
                'timeMin' => date(DATE_RFC3339),
                'timeMax' => date(DATE_RFC3339, time() + 3600*24 * 14),
                'updatedMin' => date(DATE_RFC3339, time() - 3600*24 * 14)
            )
        ));
        $request->addHeader('Authorization', sprintf('Bearer %s', $accessToken));
        try {
            $response = $request->send();
        } catch (\Guzzle\Http\Exception\BadResponseException $e) {
            $response = $e->getResponse();
            throw new \Exception(sprintf(
                'Unable to get events for Google (%d %s)',
                $response->getStatusCode(),
                $response->getReasonPhrase()
            ));
        }
        $data = json_decode($response->getBody());
        if (!property_exists($data, 'items')) {
            $output->writeln('No event received from Google calendar, done.');
            return;
        }

        // Start of data use
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $repo = $em->getRepository('UCPAbsenceBundle:Lesson');
        $now = new \DateTime();

        foreach ($data->items as $event) {
            // try {
                $event->start = \DateTime::createFromFormat(\DateTime::RFC3339, $event->start->dateTime);
                $event->end = \DateTime::createFromFormat(\DateTime::RFC3339, $event->end->dateTime);
                $event->id;
                $event->summary;

                // We've got an event, decide if we keep it and make a lesson if so.
                // From here event are expected in the future
                if ($event->status !== 'confirmed') {
                    $output->writeln(sprintf('Non confirmed event, skipping. (%s).', $event->summary));
                    continue;
                }

                // Get existing record
                $lesson = $repo->find($event->id);
                if (!$lesson) {
                    // Nothing found: this is a new one
                    $lesson = new Lesson();
                    $lesson->setId($event->id);
                    $em->persist($lesson);
                } else {
                    // Check if we should edit this object (can be an old event moved in future)
                    if ($lesson->getStart() < $now) {
                        // TODO
                        // - Check if register have been taken on this lesson
                        // - if so maybe we should duplicate event?
                        // - Do we skip it each time? (introduce inconsistency for end user)
                        continue;
                    }
                }

                $output->writeln(sprintf(
                    '[%s] %s',
                    $event->start->format(\DateTime::RFC822),
                    $event->summary
                ));

                // Modify event
                if ($lesson->getStart() != $event->start)
                    $lesson->setStart($event->start);

                if ($lesson->getEnd() != $event->end)
                    $lesson->setEnd($event->end);

                if ($lesson->getLabel() != $event->summary)
                    $lesson->setLabel($event->summary);


            // } catch (\Exception $e) {
            //     $output->writeln(print_r($event));
            // }
        }

        // Flush to DB
        $em->flush();

        $output->writeln('Sync done.');
    }

    private function getValue($key)
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $object = $em->find('UCPAbsenceBundle:KVObject', $key);

        if (!$object) {
            $object = new KVObject($key, null);
            $em->persist($object);
            $em->flush();
        }

        return $object->getValue();
    }
}