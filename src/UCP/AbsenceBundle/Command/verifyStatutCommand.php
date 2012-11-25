<?php

namespace UCP\AbsenceBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/*
class verifyStatutCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('ucp:statut:absence:check')
            ->setDescription('Verifie si l\'appel a été effectué et envoie par mail les absents a la secrétaire.')
            ->addArgument('mailTo', InputArgument::REQUIRED, 'Destinataire du mail')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //Récupere la date actuel
        $currentDate = date();
        //Récupere tous les cours qui on lieu en ce moment
        $em = $this->_container->get('doctrine.orm.entity_manager');
        $repo = $em->getRepository('UCPAbsenceBundle:Lesson');
        $lessons = $repo->findTodayOrUpcomingLessons(
                            array('start' => <date>),
                            array('start' => 'ASC'),
                            20);
        //Si date actuel > date debut + 30 min alors on envoi un mail de rappel toute les 5 min sauf s'il y a deja des absent de selectionné
        //Si date actuel > date benut + 60min alors on envoi un mail avec la liste des absents.
    }
}
*/

?>