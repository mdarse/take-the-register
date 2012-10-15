<?php

namespace UCP\AbsenceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="UCP\AbsenceBundle\Repository\AbsenceRepository")
 */
class Absence
{
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Student")
     */
    private $student;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Lesson", inversedBy="absences")
     */
    private $lesson;

    /**
     * @ORM\Column(type="boolean")
     */
    private $justified;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $reason;

    /**
     * Set justified
     *
     * @param boolean $justified
     * @return Absence
     */
    public function setJustified($justified)
    {
        $this->justified = $justified;
    
        return $this;
    }

    /**
     * Get justified
     *
     * @return boolean 
     */
    public function getJustified()
    {
        return $this->justified;
    }

    /**
     * Set reason
     *
     * @param string $reason
     * @return Absence
     */
    public function setReason($reason)
    {
        $this->reason = $reason;
    
        return $this;
    }

    /**
     * Get reason
     *
     * @return string 
     */
    public function getReason()
    {
        return $this->reason;
    }

    /**
     * Set student
     *
     * @param UCP\AbsenceBundle\Entity\Student $student
     * @return Absence
     */
    public function setStudent(\UCP\AbsenceBundle\Entity\Student $student)
    {
        $this->student = $student;
    
        return $this;
    }

    /**
     * Get student
     *
     * @return UCP\AbsenceBundle\Entity\Student 
     */
    public function getStudent()
    {
        return $this->student;
    }

    /**
     * Set lesson
     *
     * @param UCP\AbsenceBundle\Entity\Lesson $lesson
     * @return Absence
     */
    public function setLesson(\UCP\AbsenceBundle\Entity\Lesson $lesson)
    {
        $this->lesson = $lesson;
    
        return $this;
    }

    /**
     * Get lesson
     *
     * @return UCP\AbsenceBundle\Entity\Lesson 
     */
    public function getLesson()
    {
        return $this->lesson;
    }
}