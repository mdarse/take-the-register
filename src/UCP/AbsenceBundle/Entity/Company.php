<?php

namespace UCP\AbsenceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Company
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=80)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=80, nullable=true)
     */
    private $tutorName;

    /**
     * @ORM\Column(type="string", length=254, nullable=true)
     */
    private $tutorEmail;

    /**
     * @var string $phone
     *
     * @ORM\Column(type="string", length=15, nullable=true)
     */
    private $phone;

    /**
     * @var string $address
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $address;

    /**
     * @var string $city
     *
     * @ORM\Column(type="string", length=45, nullable=true)
     */
    private $city;

    /**
     * @var string $postalCode
     *
     * @ORM\Column(type="string", length=5, nullable=true)
     */
    private $postalCode;
}
