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
            'showDeleted={showDeleted}&'.
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
        $request->addHeader('Authorization', sprintf('Bearer %s', $this->getAccessToken()));
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
        $lastSync = \DateTime::createFromFormat(\DateTime::RFC3339, $this->getValue('sync.last'));
        $now = new \DateTime();

        $blacklistedEventsCount = 0;
        $addedEventsCount = 0;
        $updatedEventsCount = 0;
        $removedEventsCount = 0;
        $matchedEventsCount = 0;


        foreach ($data->items as $event) {
            // try {
                if (property_exists($event->start, 'date')) {
                    // This is an unsupported all-day event
                    $blacklistedEventsCount++;
                    continue;
                }

                $event->start   = \DateTime::createFromFormat(\DateTime::RFC3339, $event->start->dateTime);
                $event->end     = \DateTime::createFromFormat(\DateTime::RFC3339, $event->end->dateTime);
                $event->id;
                $event->summary;

                // We've got an event, decide if we keep it and make a lesson if so.
                // From here event are expected in the future
                if ($event->status === 'cancelled') {
                    $event->updated = \DateTime::createFromFormat('Y-m-d\TH:i:s.000\Z', $event->updated);
                    if ($event->updated < $lastSync) {
                        continue;
                    }
                    $lesson = $repo->find($event->id);
                    if (!$lesson) {
                        continue;
                    }
                    if ($lesson->getStart() > $now) {
                        $em->remove($lesson);
                        $removedEventsCount++;
                    }
                    // $output->writeln(sprintf('Cancelled event, skipping. (%s).', $event->summary));
                    continue;
                }
                if ($this->isBlacklisted($event)) {
                    $blacklistedEventsCount++;
                    // $output->writeln(sprintf('Event filtered by blacklist, skipping. (%s)', $event->summary));
                    continue;
                }

                // Get existing record
                $lesson = $repo->find($event->id);
                if (!$lesson) {
                    // Nothing found: this is a new one
                    $lesson = new Lesson();
                    $lesson->setId($event->id);
                    $em->persist($lesson);
                    $addedEventsCount++;
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

                // $output->writeln(sprintf(
                //     '[%s] %s',
                //     $event->start->format(\DateTime::RFC822),
                //     $event->summary
                // ));

                // Modify event
                $updated = false;
                if ($lesson->getStart() != $event->start)
                    $updated = true;
                    $lesson->setStart($event->start);

                if ($lesson->getEnd() != $event->end)
                    $updated = true;
                    $lesson->setEnd($event->end);

                if ($lesson->getLabel() != $event->summary)
                    $updated = true;
                    $lesson->setLabel($event->summary);
                if ($updated)
                    $updatedEventsCount++;

                // Try to match lesson with a professor
                if ($this->resolveProfessor($lesson)) {
                    $matchedEventsCount++;
                    // $output->writeln(sprintf('Matched with %s', $lesson->getProfessor()->getUsername()));
                }

            // } catch (\Exception $e) {
            //     print_r($event);
            // }
        }

        // Flush to DB
        $em->flush();

        $this->setValue('sync.last', $now->format(\DateTime::RFC3339));

        $output->writeln(sprintf(
            '<info>%d</info> event(s) added, <info>%d</info> updated, <info>%d</info> removed, <info>%d</info> matched and <info>%d</info> filtered.',
            $addedEventsCount,
            $updatedEventsCount,
            $removedEventsCount,
            $matchedEventsCount,
            $blacklistedEventsCount));
        $output->writeln('Sync done.');
    }

    private function isBlacklisted($event)
    {
        $blacklist = array('Mission entreprise', 'Férié');

        if (in_array($event->summary, $blacklist)) {
            return true;
        }
        // Filters events ending with "?" or "(???)"
        if (1 === preg_match('/(\?|\(\?\?\?\))$/', $event->summary)) {
            return true;
        }

        return false;
    }

    private function resolveProfessor($lesson)
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $repo = $em->getRepository('UCPAbsenceBundle:User');

        // Extract initials from lesson label
        $matches = array();
        if (1 !== preg_match('/\(([a-z]{2,3})\)$/i', $lesson->getLabel(), $matches)) {
            return false;
        }
        $initials = $matches[1];

        // We found initials, now trying to match
        $professor = $repo->findOneByInitials($initials);
        if (!$professor) {
            return false;
        }

        // We have a professor matching with initials !
        $lesson->setProfessor($professor);

        return true;
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

    private function setValue($key, $value)
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $object = $em->find('UCPAbsenceBundle:KVObject', $key);

        if (!$object) {
            $object = new KVObject($key, $value);
            $em->persist($object);
        } else {
            $object->setValue($value);
        }

        $em->flush();
    }

    private function getAccessToken()
    {
        $accessToken = $this->getValue('sync.access_token');
        if (!$accessToken) {
            throw new \Exception("OAuth2 access token unavailable.");
            // TODO get a token
        }

        if (time() >= $this->getValue('sync.access_token_expiration')) {
            // throw new \Exception("OAuth2 access token expired.");
            $this->refreshAccessToken();
        }

        return $accessToken;
    }

    private function refreshAccessToken()
    {
        $refreshToken = $this->getValue('sync.refresh_token');
        if (!$refreshToken) {
            throw new \Exception("OAuth2 refresh token unavailable.");
        }

        $client = new Client();
        $request = $client->post('https://accounts.google.com/o/oauth2/token');
        $request->addPostFields(array(
            'refresh_token' => $refreshToken,
            'client_id'     => $this->getClientId(),
            'client_secret' => $this->getClientSecret(),
            'grant_type'    => 'refresh_token'
        ));
        try {
            $response = $request->send();
        } catch (\Guzzle\Http\Exception\BadResponseException $e) {
            throw new \Exception('Unable to refresh access token.', null, $e);
        }
        $data = json_decode($response->getBody(), true);

        $this->setValue('sync.access_token', $data['access_token']);
        $this->setValue('sync.access_token_expiration', time() + $data['expires_in']);
    }
}