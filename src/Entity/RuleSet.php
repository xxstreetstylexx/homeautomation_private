<?php

namespace App\Entity;

use App\Repository\RuleSetRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RuleSetRepository::class)
 */
class RuleSet
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    
    /**
     * @ORM\ManyToOne(targetEntity=Sensors::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $Sensor;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Mode;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Operation;

    /**
     * @ORM\Column(type="integer")
     */
    private $Value;

    /**
     * @ORM\Column(type="boolean")
     */
    private $Active;

    /**
     * @ORM\ManyToOne(targetEntity=Rule::class, inversedBy="ruleSets")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Rule;

    public function __construct()
    {
        $this->Light = new ArrayCollection();
        $this->Lights = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSensor(): ?Sensors
    {
        return $this->Sensor;
    }

    public function setSensor(?Sensors $Sensor): self
    {
        $this->Sensor = $Sensor;

        return $this;
    }

    public function getMode(): ?string
    {
        return $this->Mode;
    }

    public function setMode(string $Mode): self
    {
        $this->Mode = $Mode;

        return $this;
    }

    public function getOperation(): ?string
    {
        return $this->Operation;
    }

    public function setOperation(string $Operation): self
    {
        $this->Operation = $Operation;

        return $this;
    }

    public function getValue(): ?int
    {
        return $this->Value;
    }

    public function setValue(int $Value): self
    {
        $this->Value = $Value;

        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->Active;
    }

    public function setActive(bool $Active): self
    {
        $this->Active = $Active;

        return $this;
    }

    public function getRule(): ?Rule
    {
        return $this->Rule;
    }

    public function setRule(?Rule $Rule): self
    {
        $this->Rule = $Rule;

        return $this;
    }
}
