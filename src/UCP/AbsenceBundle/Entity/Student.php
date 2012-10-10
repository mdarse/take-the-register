<?php

namespace UCP\AbsenceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="UCP\AbsenceBundle\Entity\StudentRepository")
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
     * @ORM\Column(type="string")
     */
    private $firstname;

    /**
     * @ORM\Column(type="string")
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=11, nullable=true)
     */
    private $ine;

    /**
     * @ORM\Column(type="string", length=254, nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     */
    private $phone;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $picturePath;
}
