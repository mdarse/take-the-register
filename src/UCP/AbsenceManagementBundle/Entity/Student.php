<?php

namespace UCP\AbsenceManagementBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="UCP\AbsenceManagementBundle\Entity\StudentRepository")
 */
class Student
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=45)
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=45)
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=11)
     */
    private $ine;

    /**
     * @ORM\Column(type="string", length=254)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=15)
     */
    private $phone;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $picturePath;
}
