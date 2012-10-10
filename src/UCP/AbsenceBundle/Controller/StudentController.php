<?php

namespace UCP\AbsenceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class StudentController extends Controller
{
    /**
     * @Route("/student")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }
}
