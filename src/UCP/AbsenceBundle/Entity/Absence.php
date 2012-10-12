<?php

namespace UCP\AbsenceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
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
     * @ORM\ManyToOne(targetEntity="Lesson")
     */
    private $lesson;

    /**
     * @ORM\Column(type="boolean")
     */
    private $justified;

    /**
     * @ORM\Column(type="string")
     */
    private $reason;
}
