<?php

namespace UCP\AbsenceManagementBundle\Entity;

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
     * @ORM\Column(type="string", length=80)
     */
    private $tutor;

    /**
     * @ORM\Column(type="string", length=254)
     */
    private $email;

    /**
     * @var string $phone
     *
     * @ORM\Column(type="string", length=15)
     */
    private $phone;

    /**
     * @var string $address
     *
     * @ORM\Column(type="string")
     */
    private $address;

    /**
     * @var string $city
     *
     * @ORM\Column(type="string", length=45)
     */
    private $city;

    /**
     * @var string $postalCode
     *
     * @ORM\Column(type="string", length=5)
     */
    private $postalCode;
}
