<?php

namespace UCP\AbsenceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\SerializerBundle\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass="UCP\AbsenceBundle\Repository\StudentRepository")
 * @ORM\HasLifecycleCallbacks
 * @Serializer\ExclusionPolicy("all")
 */
class Student
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Serializer\Expose
     * @Serializer\Groups({"student-list", "student-details", "lesson-details"})
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     * @Serializer\Expose
     * @Serializer\Groups({"student-list", "student-details", "lesson-details"})
     * @Assert\NotBlank
     */
    private $firstname;

    /**
     * @ORM\Column(type="string")
     * @Serializer\Expose
     * @Serializer\Groups({"student-list", "student-details", "lesson-details"})
     * @Assert\NotBlank
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=11, nullable=true)
     * @Serializer\Expose
     * @Serializer\Groups({"student-details"})
     * @Assert\Regex("/^\d{10}[A-Z]$/")
     */
    private $ine;

    /**
     * @ORM\Column(type="string", length=254, nullable=true)
     * @Serializer\Expose
     * @Serializer\Groups({"student-details"})
     * @Assert\Email
     */
    private $email;

    /**
     * International phone number according to ITU-T E.123 & ITU-T E.164
     * 
     * @ORM\Column(type="string", length=15, nullable=true)
     * @Serializer\Expose
     * @Serializer\Groups({"student-details"})
     * @Assert\Regex("/^\+(?:[0-9] ?){6,14}[0-9]$/")
     */
    private $phone;

    /**
     * @Assert\File(maxSize="6000000")
     */
    public $pictureFile;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Serializer\SerializedName("picturePath")
     * @Serializer\Accessor(getter="getWebPicturePath")
     * @Serializer\Expose
     * @Serializer\Groups({"student-list", "student-details"})
     */
    private $picturePath;

    /**
     * @ORM\OneToOne(targetEntity="Company", cascade="all", orphanRemoval=true)
     * @Serializer\Expose
     * @Serializer\Groups({"student-details"})
     */
    private $company;

    /**
     * @ORM\OneToMany(targetEntity="Absence", mappedBy="student")
     * @Serializer\Type("ArrayCollection")
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
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (null !== $this->pictureFile) {
            // do whatever you want to generate a unique name
            $this->picturePath = sha1(uniqid(mt_rand(), true)).'.'.$this->pictureFile->guessExtension();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if (null === $this->pictureFile) {
            return;
        }

        // if there is an error when moving the file, an exception will
        // be automatically thrown by move(). This will properly prevent
        // the entity from being persisted to the database on error
        $this->pictureFile->move($this->getUploadRootDir(), $this->picturePath);

        unset($this->pictureFile);
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if ($file = $this->getAbsolutePicturePath()) {
            unlink($file);
        }
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
     * Get absolute picture path
     * 
     * @return string
     */
    public function getAbsolutePicturePath()
    {
        return null === $this->picturePath ? null : $this->getUploadRootDir().'/'.$this->picturePath;
    }

    /**
     * Get picture path relative to web root
     * 
     * @return string
     */
    public function getWebPicturePath()
    {
        return null === $this->picturePath ? null : $this->getUploadDir().'/'.$this->picturePath;
    }

    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded documents should be saved
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw when displaying uploaded doc/image in the view.
        return 'uploads/students';
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