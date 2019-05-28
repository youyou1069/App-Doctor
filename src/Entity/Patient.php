<?php

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PatientRepository")
 * @UniqueEntity("nir")
 */
class Patient
{

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastName;

    /**
     * @ORM\Column(type="date")
     * @var string A "Y-m-d" formatted value
     * @Assert\LessThan("1 min")
     */
    private $birthAt;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nir;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $address;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Regex("/^[0-9]/")
     * @Assert\Length(value="5" , exactMessage="Votre code postale doit faire 5 caractères")
     */
    private $postcode;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $city;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Consultation", mappedBy="patient", cascade={"remove"})
     * @ORM\OrderBy({"createdAt" = "DESC"})
     */
    private $consultations;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $gender;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Booking", mappedBy="patient", cascade={"remove"})
     */
    private $bookings;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="patients")
     */
    private $DOCTOR;

	/**
	 * @return mixed
	 */
	public function getBookings() {
         		return $this->bookings;
         	}

	/**
	 * @param mixed $bookings
	 */
	public function setBookings( $bookings ): void {
         		$this->bookings = $bookings;
         	}

    /**
     * Patient constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->createdAt = new DateTime('now');
//        $this->medicalHistories = new ArrayCollection();
        $this->consultations = new ArrayCollection();
        $this->bookings = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
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

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }


    /**
     * Get FullName
     *
     * @return string
     */
    public function getFullName()
    {
        return $this->lastName.' '.$this->firstName;
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


    public function getBirthAt(): ?\DateTimeInterface
    {
        return $this->birthAt;
    }

    public function setBirthAt(\DateTimeInterface $birthAt): self
    {
        $this->birthAt = $birthAt;

        return $this;
    }

    public function getNir(): ?int
    {
        return $this->nir;
    }

    public function setNir(?int $nir): self
    {
        $this->nir = $nir;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): ?int
    {
        return $this->phone;
    }

    public function setPhone(?int $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getPostcode(): ?int
    {
        return $this->postcode;
    }

    public function setPostcode(int $postcode): self
    {
        $this->postcode = $postcode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param mixed $gender
     * @return Patient
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
        return $this;
    }

    /**
     * @return Collection|MedicalHistory[]
     */
    public function getMedicalHistories(): Collection
    {
        return $this->medicalHistories;
    }

    public function addMedicalHistory(MedicalHistory $medicalHistory): self
    {
        if (!$this->medicalHistories->contains($medicalHistory)) {
            $this->medicalHistories[] = $medicalHistory;
            $medicalHistory->setPatient($this);
        }

        return $this;
    }

    public function removeMedicalHistory(MedicalHistory $medicalHistory): self
    {
        if ($this->medicalHistories->contains($medicalHistory)) {
            $this->medicalHistories->removeElement($medicalHistory);
            // set the owning side to null (unless already changed)
            if ($medicalHistory->getPatient() === $this) {
                $medicalHistory->setPatient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Consultation[]
     *
     */
    public function getConsultations(): Collection
    {
        return $this->consultations;
    }

    public function addConsultation(Consultation $consultation): self
    {
        if (!$this->consultations->contains($consultation)) {
            $this->consultations[] = $consultation;
            $consultation->setPatient($this);
        }

        return $this;
    }

    public function removeConsultation(Consultation $consultation): self
    {
        if ($this->consultations->contains($consultation)) {
            $this->consultations->removeElement($consultation);
            // set the owning side to null (unless already changed)
            if ($consultation->getPatient() === $this) {
                $consultation->setPatient(null);
            }
        }

        return $this;
    }


    /**
     * @return Collection|Booking[]
     */
    public function getBooking(): Collection
    {
        return $this->bookings;
    }

    public function addBooking(Booking $booking): self
    {
        if (!$this->bookings->contains($booking)) {
            $this->bookings[] = $booking;
            $booking->setPatient($this);
        }

        return $this;
    }

    public function removeBooking(Booking $booking): self
    {
        if ($this->bookings->contains($booking)) {
            $this->bookings->removeElement($booking);
            // set the owning side to null (unless already changed)
            if ($booking->getPatient() === $this) {
                $booking->setPatient(null);
            }
        }

        return $this;
    }

    public function getDOCTOR(): ?User
    {
        return $this->DOCTOR;
    }

    public function setDOCTOR(?User $DOCTOR): self
    {
        $this->DOCTOR = $DOCTOR;

        return $this;
    }
}
