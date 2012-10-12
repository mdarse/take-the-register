<?php

namespace UCP\AbsenceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Absence
{
    /**
     * @ORM\Column(type="boolean")
     */
    private $justified;

    /**
     * @ORM\Column(type="string")
     */
    private $reason;
}
