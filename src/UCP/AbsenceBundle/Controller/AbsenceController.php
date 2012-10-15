<?php

namespace UCP\AbsenceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use JMS\SecurityExtraBundle\Annotation\Secure;
use UCP\AbsenceBundle\Entity\Lesson;

class AbsenceController extends Controller
{
    /**
     * @Secure("ROLE_USER")
     * @Route("/lesson", name="lesson")
     * @Template("UCPAbsenceBundle:Absence:index.html.twig")
     */
    public function indexAction()
    {
    	$em = $this->getDoctrine()->getManager();

    	$lessons = $em->getRepository('UCPAbsenceBundle:Lesson')->findUpcomingLessons(10);

        return array('upcoming_lessons' => $lessons);
    }

    /**
     * @Secure("ROLE_USER")
     * @Route("/lesson/{id}", name="lesson_show")
     * @Template("UCPAbsenceBundle:Absence:lesson.html.twig")
     */
    public function showAction(Lesson $lesson)
    {
    	$em = $this->getDoctrine()->getManager();
    	$repo = $em->getRepository('UCPAbsenceBundle:Lesson');

    	$previousLesson = $repo->findPreviousLesson($lesson);
    	$nextLesson     = $repo->findNextLesson($lesson);

        return array(
        	'lesson'          => $lesson,
        	'previous_lesson' => $previousLesson,
        	'next_lesson'     => $nextLesson
        );
    }
}