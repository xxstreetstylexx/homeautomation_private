<?php

namespace App\Entity;

use App\Repository\LightLogRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LightLogRepository::class)
 */
class LightLog
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $datetime;

    /**
     * @ORM\Column(type="boolean")
     */
    private $stateOn;

    /**
     * @ORM\Column(type="integer")
     */
    private $stateBri;

    /**
     * @ORM\Column(type="integer")
     */
    private $stateHue;

    /**
     * @ORM\Column(type="integer")
     */
    private $stateSat;

    /**
     * @ORM\Column(type="json")
     */
    private $stateXY = [];

    /**
     * @ORM\Column(type="boolean")
     */
    private $reachable;

    /**
     * @ORM\ManyToOne(targetEntity=Lights::class, inversedBy="lightLogs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $light;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDatetime(): ?\DateTimeInterface
    {
        return $this->datetime;
    }

    public function setDatetime(\DateTimeInterface $datetime): self
    {
        $this->datetime = $datetime;

        return $this;
    }

    public function getStateOn(): ?bool
    {
        return $this->stateOn;
    }

    public function setStateOn(bool $stateOn): self
    {
        $this->stateOn = $stateOn;

        return $this;
    }

    public function getStateBri(): ?int
    {
        return $this->stateBri;
    }

    public function setStateBri(int $stateBri): self
    {
        $this->stateBri = $stateBri;

        return $this;
    }

    public function getStateHue(): ?int
    {
        return $this->stateHue;
    }

    public function setStateHue(int $stateHue): self
    {
        $this->stateHue = $stateHue;

        return $this;
    }

    public function getStateSat(): ?int
    {
        return $this->stateSat;
    }

    public function setStateSat(int $stateSat): self
    {
        $this->stateSat = $stateSat;

        return $this;
    }

    public function getStateXY(): ?array
    {
        return $this->stateXY;
    }

    public function setStateXY(array $stateXY): self
    {
        $this->stateXY = $stateXY;

        return $this;
    }

    public function getReachable(): ?bool
    {
        return $this->reachable;
    }

    public function setReachable(bool $reachable): self
    {
        $this->reachable = $reachable;

        return $this;
    }

    public function getLight(): ?Lights
    {
        return $this->light;
    }

    public function setLight(?Lights $light): self
    {
        $this->light = $light;

        return $this;
    }

}
