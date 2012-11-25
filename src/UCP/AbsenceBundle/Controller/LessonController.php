<?php

namespace UCP\AbsenceBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use UCP\AbsenceBundle\Entity\Lesson;

class LessonController extends FOSRestController
{
    /**
     * Retrieve a collection of lessons
     *
     * @ApiDoc()
     * @Rest\View(serializerGroups={"lesson-list"})
     */
    public function getLessonsAction()
    {
        $em = $this->getDoctrine()->getManager();

        $lessons = $em->getRepository('UCPAbsenceBundle:Lesson')->findTodayOrUpcomingLessons(10);

        return $this->view($lessons);
    }

    /**
     * Retrieve a single lesson
     *
     * @ApiDoc()
     * @Rest\View(serializerGroups={"lesson-details"})
     */
    public function getLessonAction(Lesson $lesson)
    {
        return $this->view($lesson);
    }
}
