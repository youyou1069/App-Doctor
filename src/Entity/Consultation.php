<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ConsultationRepository")
 */
class Consultation
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
    private $name;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="text",  nullable=true)
     */
    private $diagnostic;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $treatment;

    /**
     * @ORM\Column(type="text",  nullable=true)
     */
    private $decision;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Patient", inversedBy="consultations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $patient;

//    /**
//     * @ORM\ManyToOne(targetEntity="App\Entity\MedicalHistory", inversedBy="consultations", cascade={"persist"})
//     */
//    private $medHistory;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $allergies;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $medFamilyHistory;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $medTreatmentHistory;


    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $others;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Drug", mappedBy="consultation")
     */
    private $drug;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Drug", inversedBy="consultations")
     */
    private $medicament;



    /**
     * @return mixed
     */
    public function getOthers()
    {
        return $this->others;
    }

    /**
     * @param mixed $others
     * @return Consultation
     */
    public function setOthers($others)
    {
        $this->others = $others;
        return $this;
    }

//    /**
//     * @ORM\ManyToOne(targetEntity="App\Entity\Doctor", inversedBy="consultations")
//     * @ORM\JoinColumn(nullable=false)
//     */
//    private $doctor;

    /**
     * Consultation constructor.
     * @param $createdAt
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime('now');
        $this->drug = new ArrayCollection();
        $this->medicament = new ArrayCollection();

    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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

    public function getDiagnostic(): ?string
    {
        return $this->diagnostic;
    }

    public function setDiagnostic(?string $diagnostic): self
    {
        $this->diagnostic = $diagnostic;

        return $this;
    }

    public function getTreatment(): ?string
    {
        return $this->treatment;
    }

    public function setTreatment(?string $treatment): self
    {
        $this->treatment = $treatment;

        return $this;
    }

    public function getDecision(): ?string
    {
        return $this->decision;
    }

    public function setDecision(?string $decision): self
    {
        $this->decision = $decision;

        return $this;
    }

    public function getPatient(): ?Patient
    {
        return $this->patient;
    }

    public function setPatient(?Patient $patient): self
    {
        $this->patient = $patient;

        return $this;
    }
//
//    public function getMedHistory(): ?MedicalHistory
//    {
//        return $this->medHistory;
//    }
//
//    public function setMedHistory(?MedicalHistory $medHistory): self
//    {
//        $this->medHistory = $medHistory;
//
//        return $this;
//    }

    public function getAllergies(): ?string
    {
        return $this->allergies;
    }

    public function setAllergies(?string $allergies): self
    {
        $this->allergies = $allergies;

        return $this;
    }

    public function getMedFamilyHistory(): ?string
    {
        return $this->medFamilyHistory;
    }

    public function setMedFamilyHistory(?string $medFamilyHistory): self
    {
        $this->medFamilyHistory = $medFamilyHistory;

        return $this;
    }

    public function getMedTreatmentHistory(): ?string
    {
        return $this->medTreatmentHistory;
    }

    public function setMedTreatmentHistory(?string $medTreatmentHistory): self
    {
        $this->medTreatmentHistory = $medTreatmentHistory;

        return $this;
    }

    /**
     * @return Collection|Drug[]
     */
    public function getDrug(): Collection
    {
        return $this->drug;
    }

    public function addDrug(Drug $drug): self
    {
        if (!$this->drug->contains($drug)) {
            $this->drug[] = $drug;
            $drug->setConsultation($this);
        }

        return $this;
    }

    public function removeDrug(Drug $drug): self
    {
        if ($this->drug->contains($drug)) {
            $this->drug->removeElement($drug);
            // set the owning side to null (unless already changed)
            if ($drug->getConsultation() === $this) {
                $drug->setConsultation(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Drug[]
     */
    public function getMedicament(): Collection
    {
        return $this->medicament;
    }

    public function addMedicament(Drug $medicament): self
    {
        if (!$this->medicament->contains($medicament)) {
            $this->medicament[] = $medicament;
        }

        return $this;
    }

    public function removeMedicament(Drug $medicament): self
    {
        if ($this->medicament->contains($medicament)) {
            $this->medicament->removeElement($medicament);
        }

        return $this;
    }
//

//	/**
//	 * @return string
//	 */
//	public function __toString() : string
//	{
//		return sprintf('%s (%s)', $this->get(), $this->getName());
//	}

//    public function getDoctor(): ?Doctor
//    {
//        return $this->doctor;
//    }
//
//    public function setDoctor(?Doctor $doctor): self
//    {
//        $this->doctor = $doctor;
//
//        return $this;
//    }
}
