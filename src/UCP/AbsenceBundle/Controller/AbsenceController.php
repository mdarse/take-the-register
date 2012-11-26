<?php

namespace UCP\AbsenceBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use UCP\AbsenceBundle\Entity\Absence;

class AbsenceController extends FOSRestController
{
    /**
     * Retrieve a collection of absences
     *
     * @ApiDoc()
     * @Rest\View(serializerGroups={"absences"})
     * @Rest\QueryParam(name="page", requirements="\d+", default="1", description="Page of the overview.")
     * @Rest\QueryParam(name="count", requirements="\d+", default="20", description="Item count limit")
     * @Rest\QueryParam(name="lesson", description="Lesson id to retrieve absences for.")
     * @Rest\QueryParam(name="student", requirements="\d+",description="Student id to retrieve absences for.")
     */
    public function getAbsencesAction(ParamFetcher $paramFetcher)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('UCPAbsenceBundle:Absence');

        $limit = min($paramFetcher->get('count'), 50); // Hard limit to 50 items
        $offset = $limit * ($paramFetcher->get('page') - 1);
        $student = $paramFetcher->get('student');
        $lesson = $paramFetcher->get('lesson');

        $criteria = array();
        if ($student) {
            $criteria['student'] = $em->getReference('UCPAbsenceBundle:Student', $student);
        }
        if ($lesson) {
            $criteria['lesson'] = $em->getReference('UCPAbsenceBundle:Lesson', $lesson);
        }

        $absences = $repo->findBy($criteria, array(), $limit, $offset);

        return $this->view($absences);
    }

    /**
     * Creates an absence
     *
     * @ApiDoc()
     * @Rest\View(serializerGroups={"absences"}, statusCode="201")
     */
    public function postAbsencesAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('UCPAbsenceBundle:Absence');

        $lessonId = $request->request->get('lesson');
        $lesson = $em->getReference('UCPAbsenceBundle:Lesson', $lessonId);
        $studentId = $request->request->get('student');
        $student = $em->getReference('UCPAbsenceBundle:Student', $studentId);

        $absence = new Absence($lesson, $student);
        $absence->setJustified(false);
        $em->persist($absence);
        $em->flush();

        return $this->view($absence);
    }

    /**
     * Delete an absence
     *
     * @ApiDoc()
     * @Rest\View(serializerGroups={"absences"}, statusCode="204")
     */
    public function deleteAbsenceAction($publicId)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('UCPAbsenceBundle:Absence');

        $absence = $repo->find($this->getIndentifier($publicId));
        $em->remove($absence);
        $em->flush();

        return $this->view();
    }

    private function getIndentifier($publicId)
    {
        $split = explode('-', $publicId, 2);
        return array(
            'lesson' => $split[0],
            'student' => $split[1]
        );
    }
}
