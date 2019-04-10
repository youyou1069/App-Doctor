<?php

namespace App\Entity;

class PatientSearch
{

    /**
     * @var string
     */
    private $lastName;

    /**
     * @var string
     */
    private $firstName;

    /**
     * @var Int|null
     */
    private $postcode;

    /**
     * @var int|null
     */
    private $nir;

    /**
     * @return Int|null
     */
    public function getPostcode(): ?Int
    {
        return $this->postcode;
    }

    /**
     * @param Int|null $postcode
     */
    public function setPostcode(?Int $postcode): void
    {
        $this->postcode = $postcode;
    }


    public function getId(): ?int
    {
        /** @noinspection PhpUndefinedFieldInspection */
        return $this->id;
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

    public function getNir(): ?int
    {
        return $this->nir;
    }

    public function setNir(?int $nir): self
    {
        $this->nir = $nir;

        return $this;
    }
}
