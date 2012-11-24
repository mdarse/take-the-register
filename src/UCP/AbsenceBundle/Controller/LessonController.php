<?php

namespace UCP\AbsenceBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use UCP\AbsenceBundle\Entity\Lesson;

class LessonController extends FOSRestController
{
    /**
     * Retrieve a collection of lessons
     *
     * @ApiDoc()
     */
    public function getLessonsAction()
    {
        $em = $this->getDoctrine()->getManager();

        $lessons = $em->getRepository('UCPAbsenceBundle:Lesson')->findTodayOrUpcomingLessons(10);

        $view = $this->view($lessons, 200);

        return $this->handleView($view);
    }

    /**
     * Retrieve a single lesson
     *
     * @ApiDoc()
     */
    public function getLessonAction(Lesson $lesson)
    {
        $view = $this->view($lesson, 200);

        return $this->handleView($view);
    }
}
