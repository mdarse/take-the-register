<?php

namespace UCP\AbsenceBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
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
     * @Rest\View(serializerGroups={"student-list"})
     */
    public function getStudentsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('UCPAbsenceBundle:Student');
        $students = $repo->findBy(
            array(),
            array('lastname' => 'ASC')
        );

        return $this->view($students);
    }

    /**
     * Retrieve a single student
     *
     * @ApiDoc()
     * @Rest\View(serializerGroups={"student-details"})
     */
    public function getStudentAction(Student $student)
    {
        return $this->view($student);
    }

    /**
     * Create a new student
     *
     * @ApiDoc()
     */
    public function postStudentsAction()
    {
        return $this->processForm(new Student(), true);
    }

    /**
     * Save a single student
     *
     * @ApiDoc()
     */
    public function putStudentAction(Student $student)
    {
        return $this->processForm($student);
    }

    private function processForm(Student $student, $isNew = false)
    {
        $statusCode = $isNew ? 201 : 200;

        $form = $this->createForm(new StudentType(), $student);
        $form->bind($this->getRequest());

        if ($form->isValid()) {
            $em = $this->get('doctrine.orm.entity_manager');
            if ($isNew) {
                $em->persist($student);
            }
            $em->flush();

            $serializer = $this->get('serializer');
            $data = $serializer->serialize($student, 'json');

            return new Response($data, $statusCode);
        }

        $view = $this->view($form, 400);
        return $this->handleView($view);
    }

    /**
     * Delete a single student
     *
     * @ApiDoc()
     */
    public function deleteStudentAction(Student $student)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $em->remove($student);
        $em->flush();

        $response = new Response();
        $response->setStatusCode(204);
        return $response;
    }
}
