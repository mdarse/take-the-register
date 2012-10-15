<?php

namespace UCP\AbsenceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use JMS\SecurityExtraBundle\Annotation\Secure;
use UCP\AbsenceBundle\Entity\Lesson;
use UCP\AbsenceBundle\Entity\Student;
use UCP\AbsenceBundle\Entity\Absence;

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
        
        $studentRepository = $em->getRepository('UCPAbsenceBundle:Student');
        $allStudents       = $studentRepository->findAll();
        $absentStudents    = $studentRepository->findAbsentByLesson($lesson);

        $repo           = $em->getRepository('UCPAbsenceBundle:Lesson');
        $previousLesson = $repo->findPreviousLesson($lesson);
        $nextLesson     = $repo->findNextLesson($lesson);

        return array(
            'students'        => $allStudents,
            'absent_students' => $absentStudents,
            'lesson'          => $lesson,
            'previous_lesson' => $previousLesson,
            'next_lesson'     => $nextLesson
        );
    }

    /**
     * @Secure("ROLE_USER")
     * @Route("/lesson/{lesson}/{student}/absent", name="lesson_absence_create")
     */
    public function markAbsentAction(Lesson $lesson, Student $student)
    {
        if (!$this->editAllowed($lesson)) {
            throw new AccessDeniedException();
        }

        $em = $this->getDoctrine()->getManager();

        $absence = new Absence($lesson, $student);
        $absence->setJustified(false);

        $em->merge($absence);
        $em->flush();

        return $this->redirect($this->generateUrl('lesson_show', array('id' => $lesson->getId())));
    }

    /**
     * @Secure("ROLE_USER")
     * @Route("/lesson/{lesson}/{student}/present", name="lesson_absence_delete")
     */
    public function markPresentAction(Lesson $lesson, Student $student)
    {
        if (!$this->editAllowed($lesson)) {
            throw new AccessDeniedException();
        }

        $em = $this->getDoctrine()->getManager();

        $absence = $em->find('UCPAbsenceBundle:Absence', array(
            'lesson'  => $lesson->getId(),
            'student' => $student->getId()
        ));

        if ($absence) {
            $em->remove($absence);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('lesson_show', array('id' => $lesson->getId())));
    }

    private function editAllowed(Lesson $lesson)
    {
        $sc = $this->get('security.context');

        if (true === $sc->isGranted('ROLE_SECRETARY') || $lesson->getEnd() > new \DateTime()) {
            return true;
        }

        return false;
    }
}
