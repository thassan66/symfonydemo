<?php

namespace App\Entity;

use App\Repository\CountryRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CountryRepository::class)
 */
class Country
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * var string
     * @ORM\Column(type="string", length=255)
     */
    private string $name;

    /**
     * var string
     * @ORM\Column(type="string", length=2)
     */
    private $alpha2Code;

    /**
     * var string
     * @ORM\Column(type="string", length=5)
     */
    private string $currencyCode;

    /**
     * Continent continent
     * @ORM\ManyToOne(targetEntity=Continent::class)
     * @ORM\JoinColumn(name="continent", referencedColumnName="id")
     */
    private Continent $continent;

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

    public function getAlpha2Code(): ?string
    {
        return $this->alpha2Code;
    }

    public function setAlpha2Code(string $alpha2Code): self
    {
        $this->alpha2Code = $alpha2Code;

        return $this;
    }

    public function getCurrencyCode(): ?string
    {
        return $this->currencyCode;
    }

    public function setCurrencyCode(string $currencyCode): self
    {
        $this->currencyCode = $currencyCode;

        return $this;
    }

    public function getContinent(): ?Continent
    {
        return $this->continent;
    }

    public function setContinent(?Continent $continent): self
    {
        $this->continent = $continent;

        return $this;
    }
}
