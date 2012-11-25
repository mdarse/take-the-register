<?php

namespace UCP\AbsenceBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class notificationCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('ucp:notification:send')
            ->setDescription('envoyer un mail')
            ->addArgument('mailTo', InputArgument::REQUIRED, 'Destinataire du mail')
            ->addArgument('subject', InputArgument::REQUIRED, 'Sujet du message')
            ->addArgument('content', InputArgument::REQUIRED, 'Contenu du message')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $mailTo = $input->getArgument('mailTo');
        $subject = $input->getArgument('subject');
        $content = $input->getArgument('content');
        if ($mailTo&&$subject&&$content) {
            $message = \Swift_Message::newInstance()
                ->setSubject($subject)
                ->setFrom('no-reply@ttr.com')
                ->setTo($mailTo)
                ->setBody($this->renderView('HelloBundle:Hello:email.txt.twig', $content))
            ;
            $this->get('mailer')->send($message);
        }
    }
}

?>