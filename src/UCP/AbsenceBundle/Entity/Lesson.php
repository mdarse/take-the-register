<?php

namespace UCP\AbsenceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\SerializerBundle\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass="UCP\AbsenceBundle\Repository\LessonRepository")
 * @Serializer\ExclusionPolicy("all")
 */
class Lesson
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string")
     * @Serializer\Expose
     * @Serializer\Groups({"lesson-list", "lesson-details"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @Serializer\Expose
     * @Serializer\Groups({"lesson-list","lesson-details"})
     */
    private $professor;

    /**
     * @ORM\Column(type="string")
     * @Serializer\Expose
     * @Serializer\Groups({"lesson-list", "lesson-details"})
     * @Serializer\ReadOnly
     */
    private $label;

    /**
     * @ORM\Column(type="datetime")
     * @Serializer\Expose
     * @Serializer\Groups({"lesson-list", "lesson-details"})
     * @Serializer\ReadOnly
     */
    private $start;

    /**
     * @ORM\Column(type="datetime")
     * @Serializer\Expose
     * @Serializer\Groups({"lesson-list", "lesson-details"})
     * @Serializer\ReadOnly
     */
    private $end;

    /**
     * @ORM\OneToMany(targetEntity="Absence", mappedBy="lesson")
     * @Serializer\Type("ArrayCollection")
     * @Serializer\Expose
     * @Serializer\Groups({"lesson-details"})
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
     * Set id
     *
     * @param string $id
     * @return Lesson
     */
    public function setId($id)
    {
        $this->id = $id;
    
        return $this;
    }

    /**
     * Get id
     *
     * @return string 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set label
     *
     * @param string $label
     * @return Lesson
     */
    public function setLabel($label)
    {
        $this->label = $label;
    
        return $this;
    }

    /**
     * Get label
     *
     * @return string 
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set start
     *
     * @param \DateTime $start
     * @return Lesson
     */
    public function setStart($start)
    {
        $this->start = $start;
    
        return $this;
    }

    /**
     * Get start
     *
     * @return \DateTime 
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * Set end
     *
     * @param \DateTime $end
     * @return Lesson
     */
    public function setEnd($end)
    {
        $this->end = $end;
    
        return $this;
    }

    /**
     * Get end
     *
     * @return \DateTime 
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * Set professor
     *
     * @param UCP\AbsenceBundle\Entity\User $professor
     * @return Lesson
     */
    public function setProfessor(\UCP\AbsenceBundle\Entity\User $professor = null)
    {
        $this->professor = $professor;
    
        return $this;
    }

    /**
     * Get professor
     *
     * @return UCP\AbsenceBundle\Entity\User 
     */
    public function getProfessor()
    {
        return $this->professor;
    }

    /**
     * Add absences
     *
     * @param UCP\AbsenceBundle\Entity\Absence $absences
     * @return Lesson
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