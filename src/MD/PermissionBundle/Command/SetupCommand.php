<?php

namespace MD\PermissionBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class SetupCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('cache:setup-permissions')
            ->setDescription('Set app/cache & app/logs permissions')
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
        $kernel = $this->getContainer()->get('kernel');

        $cacheDir = $kernel->getRootDir() . '/cache';
        // $cacheDir = $kernel->getCacheDir(); // Gives app/cache/<env>
        $logDir = $kernel->getLogDir();

        $a = exec(sprintf("sudo chmod +a \"_www allow delete,write,append,file_inherit,directory_inherit\" %s %s", $cacheDir, $logDir));
        $b = exec(sprintf("sudo chmod +a \"`whoami` allow delete,write,append,file_inherit,directory_inherit\" %s %s", $cacheDir, $logDir));

        if ($a === '' && $b === '') {
            $output->writeln('Permissions OK.');
        } else {
            throw new \Exception('Error while setting permissions.');
        }
    }
}