<?php
/**
 * Created by PhpStorm.
 * User: REMYELFI
 * Date: 26/03/2019
 * Time: 12:06
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Patient", mappedBy="DOCTOR", cascade={"remove"})
     */
    private $patients;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstName;



    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Booking", mappedBy="doctor", cascade={"remove"})
     */
    private $bookings;


    public function __construct()
    {
        parent::__construct();
        $this->patients = new ArrayCollection();
        $this->bookings = new ArrayCollection();
    }

    /**
     * @return Collection|Patient[]
     */
    public function getPatients(): Collection
    {
        return $this->patients;
    }

    public function addPatient(Patient $patient): self
    {
        if (!$this->patients->contains($patient)) {
            $this->patients[] = $patient;
            $patient->setDOCTOR($this);
        }

        return $this;
    }

    public function removePatient(Patient $patient): self
    {
        if ($this->patients->contains($patient)) {
            $this->patients->removeElement($patient);
            // set the owning side to null (unless already changed)
            if ($patient->getDOCTOR() === $this) {
                $patient->setDOCTOR(null);
            }
        }

        return $this;
    }

    /**
     * Get FullName
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Get FullName
     *
     * @return string
     */
    public function getFullName()
    {
        return $this->lastName . ' ' . $this->firstName;
    }

    /**
     * Get FullName
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getFullName();
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @return Collection|Booking[]
     */
    public function getBookings(): Collection
    {
        return $this->bookings;
    }

    public function addBooking(Booking $booking): self
    {
        if (!$this->bookings->contains($booking)) {
            $this->bookings[] = $booking;
            $booking->setDoctor($this);
        }

        return $this;
    }

    public function removeBooking(Booking $booking): self
    {
        if ($this->bookings->contains($booking)) {
            $this->bookings->removeElement($booking);
            // set the owning side to null (unless already changed)
            if ($booking->getDoctor() === $this) {
                $booking->setDoctor(null);
            }
        }

        return $this;
    }
}