<?php

namespace UCP\AbsenceBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use JMS\SecurityExtraBundle\Annotation\Secure;
use UCP\AbsenceBundle\Entity\Student;
use UCP\AbsenceBundle\Form\StudentType;

class StudentController extends FOSRestController
{
    /**
     * Retrieve a collection of all students
     *
     * @ApiDoc()
     */
    public function getStudentsAction()
    {
        $em = $this->getDoctrine()->getManager();

        $students = $em->getRepository('UCPAbsenceBundle:Student')->findAll();

        $view = $this->view($students, 200);

        return $this->handleView($view);
    }

    /**
     * Retrieve a single student
     *
     * @ApiDoc()
     */
    public function getStudentAction(Student $student)
    {
        $view = $this->view($student, 200);

        return $this->handleView($view);
    }
}
