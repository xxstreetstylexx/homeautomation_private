<?php

namespace App\Entity;

use App\Repository\ActionsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ActionsRepository::class)
 */
class Actions
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
     * @ORM\ManyToMany(targetEntity=Lights::class, inversedBy="actions")
     */
    private $Lights;

    /**
     * @ORM\Column(type="time")
     */
    private $StartTime;

    /**
     * @ORM\Column(type="time")
     */
    private $EndTime;

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

    /**
     * @return Collection|Lights[]
     */
    public function getLights(): Collection
    {
        return $this->Lights;
    }

    public function addLight(Lights $light): self
    {
        if (!$this->Lights->contains($light)) {
            $this->Lights[] = $light;
        }

        return $this;
    }

    public function removeLight(Lights $light): self
    {
        $this->Lights->removeElement($light);

        return $this;
    }

    public function getStartTime(): ?\DateTimeInterface
    {
        return $this->StartTime;
    }

    public function setStartTime(\DateTimeInterface $StartTime): self
    {
        $this->StartTime = $StartTime;

        return $this;
    }

    public function getEndTime(): ?\DateTimeInterface
    {
        return $this->EndTime;
    }

    public function setEndTime(\DateTimeInterface $EndTime): self
    {
        $this->EndTime = $EndTime;

        return $this;
    }
}
