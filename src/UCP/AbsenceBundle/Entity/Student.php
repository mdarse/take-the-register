<?php

namespace UCP\AbsenceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\SerializerBundle\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass="UCP\AbsenceBundle\Repository\StudentRepository")
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
     * @Assert\NotBlank
     */
    private $firstname;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=11, nullable=true)
     * @Assert\Regex("/^\d{10}[A-Z]$/")
     */
    private $ine;

    /**
     * @ORM\Column(type="string", length=254, nullable=true)
     * @Assert\Email
     */
    private $email;

    /**
     * International phone number according to ITU-T E.123 & ITU-T E.164
     * 
     * @ORM\Column(type="string", length=15, nullable=true)
     * @Assert\Regex("/^\+(?:[0-9] ?){6,14}[0-9]$/")
     */
    private $phone;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Serializer\Exclude
     */
    private $picturePath;

    /**
     * @ORM\OneToOne(targetEntity="Company", cascade="all", orphanRemoval=true)
     */
    private $company;

    /**
     * @ORM\OneToMany(targetEntity="Absence", mappedBy="student")
     * @Serializer\Type("ArrayCollection")
     * @Serializer\Exclude
     */
    private $absences;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->absences = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set firstname
     *
     * @param string $firstname
     * @return Student
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    
        return $this;
    }

    /**
     * Get firstname
     *
     * @return string 
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     * @return Student
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    
        return $this;
    }

    /**
     * Get lastname
     *
     * @return string 
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set ine
     *
     * @param string $ine
     * @return Student
     */
    public function setIne($ine)
    {
        $this->ine = $ine;
    
        return $this;
    }

    /**
     * Get ine
     *
     * @return string 
     */
    public function getIne()
    {
        return $this->ine;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Student
     */
    public function setEmail($email)
    {
        $this->email = $email;
    
        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return Student
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    
        return $this;
    }

    /**
     * Get phone
     *
     * @return string 
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set picturePath
     *
     * @param string $picturePath
     * @return Student
     */
    public function setPicturePath($picturePath)
    {
        $this->picturePath = $picturePath;
    
        return $this;
    }

    /**
     * Get picturePath
     *
     * @return string 
     */
    public function getPicturePath()
    {
        return $this->picturePath;
    }

    /**
     * Set company
     *
     * @param UCP\AbsenceBundle\Entity\Company $company
     * @return Student
     */
    public function setCompany(\UCP\AbsenceBundle\Entity\Company $company = null)
    {
        $this->company = $company;
    
        return $this;
    }

    /**
     * Get company
     *
     * @return UCP\AbsenceBundle\Entity\Company 
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * Add absences
     *
     * @param UCP\AbsenceBundle\Entity\Absence $absences
     * @return Student
     */
    public function addAbsence(\UCP\AbsenceBundle\Entity\Absence $absences)
    {
        $this->absences[] = $absences;
    
        return $this;
    }

    /**
     * Remove absences
     *
     * @param UCP\AbsenceBundle\Entity\Absence $absences
     */
    public function removeAbsence(\UCP\AbsenceBundle\Entity\Absence $absences)
    {
        $this->absences->removeElement($absences);
    }

    /**
     * Get absences
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getAbsences()
    {
        return $this->absences;
    }
}