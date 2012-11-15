<?php

namespace UCP\AbsenceBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use UCP\AbsenceBundle\Entity\Lesson;

class LessonController extends FOSRestController
{
    public function getLessonsAction()
    {
        $em = $this->getDoctrine()->getManager();

        $lessons = $em->getRepository('UCPAbsenceBundle:Lesson')->findAll();

        $view = $this->view($lessons, 200);

        return $this->handleView($view);
    }

    public function getLessonAction(Lesson $lesson)
    {
        $view = $this->view($lesson, 200);

        return $this->handleView($view);
    }
}
