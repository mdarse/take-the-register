<?php

namespace UCP\AbsenceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\SerializerBundle\Annotation\Exclude;
use JMS\SerializerBundle\Annotation\SerializedName;

/**
 * @ORM\Entity
 */
class Company
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Exclude
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=80)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=80, nullable=true)
     * @SerializedName("tutorName")
     */
    private $tutorName;

    /**
     * @ORM\Column(type="string", length=254, nullable=true)
     * @SerializedName("tutorEmail")
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
     * @SerializedName("postalCode")
     */
    private $postalCode;

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
     * Set name
     *
     * @param string $name
     * @return Company
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set tutorName
     *
     * @param string $tutorName
     * @return Company
     */
    public function setTutorName($tutorName)
    {
        $this->tutorName = $tutorName;
    
        return $this;
    }

    /**
     * Get tutorName
     *
     * @return string 
     */
    public function getTutorName()
    {
        return $this->tutorName;
    }

    /**
     * Set tutorEmail
     *
     * @param string $tutorEmail
     * @return Company
     */
    public function setTutorEmail($tutorEmail)
    {
        $this->tutorEmail = $tutorEmail;
    
        return $this;
    }

    /**
     * Get tutorEmail
     *
     * @return string 
     */
    public function getTutorEmail()
    {
        return $this->tutorEmail;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return Company
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
     * Set address
     *
     * @param string $address
     * @return Company
     */
    public function setAddress($address)
    {
        $this->address = $address;
    
        return $this;
    }

    /**
     * Get address
     *
     * @return string 
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set city
     *
     * @param string $city
     * @return Company
     */
    public function setCity($city)
    {
        $this->city = $city;
    
        return $this;
    }

    /**
     * Get city
     *
     * @return string 
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set postalCode
     *
     * @param string $postalCode
     * @return Company
     */
    public function setPostalCode($postalCode)
    {
        $this->postalCode = $postalCode;
    
        return $this;
    }

    /**
     * Get postalCode
     *
     * @return string 
     */
    public function getPostalCode()
    {
        return $this->postalCode;
    }
}