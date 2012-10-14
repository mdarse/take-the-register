<?php

namespace UCP\AbsenceBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;


class SyncCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('ucp:lesson:sync')
            ->setDescription('Sync lessons to come with Google calendar')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("Hello");
    }
}