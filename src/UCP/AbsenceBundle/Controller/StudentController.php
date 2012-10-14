<?php

namespace UCP\AbsenceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;

class StudentController extends Controller
{
    /**
     * @Secure(roles="ROLE_SECRETARY, ROLE_ADMIN")
     * @Route("/student")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('UCPAbsenceBundle:Student');
        $students = $repo->findAll();

        return array('students' => $students);
    }
}
