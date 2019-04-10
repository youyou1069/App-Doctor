<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MedicalHistoryRepository")
 */
class MedicalHistory
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $allergies;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $other;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $treatment;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Patient", inversedBy="medicalHistories")
     * @ORM\JoinColumn(nullable=false)
     */
    private $patient;
//
//    /**
//     * @ORM\OneToMany(targetEntity="App\Entity\Consultation", mappedBy="medHistory")
//     */
//    private $consultations;

//    public function __construct()
//    {
//        $this->consultations = new ArrayCollection();
//    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return MedicalHistory
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAllergies()
    {
        return $this->allergies;
    }

    /**
     * @param mixed $allergies
     * @return MedicalHistory
     */
    public function setAllergies($allergies)
    {
        $this->allergies = $allergies;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOther()
    {
        return $this->other;
    }

    /**
     * @param mixed $other
     * @return MedicalHistory
     */
    public function setOther($other)
    {
        $this->other = $other;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTreatment()
    {
        return $this->treatment;
    }

    /**
     * @param mixed $treatment
     * @return MedicalHistory
     */
    public function setTreatment($treatment)
    {
        $this->treatment = $treatment;
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

//    /**
//     * @return Collection|Consultation[]
//     */
//    public function getConsultations(): Collection
//    {
//        return $this->consultations;
//    }
//
//    public function addConsultation(Consultation $consultation): self
//    {
//        if (!$this->consultations->contains($consultation)) {
//            $this->consultations[] = $consultation;
//            $consultation->setMedHistory($this);
//        }
//
//        return $this;
//    }
//
//    public function removeConsultation(Consultation $consultation): self
//    {
//        if ($this->consultations->contains($consultation)) {
//            $this->consultations->removeElement($consultation);
//            // set the owning side to null (unless already changed)
//            if ($consultation->getMedHistory() === $this) {
//                $consultation->setMedHistory(null);
//            }
//        }
//
//        return $this;
//    }
}
