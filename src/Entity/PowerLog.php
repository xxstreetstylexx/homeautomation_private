<?php

namespace App\Entity;

use App\Repository\PowerLogRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PowerLogRepository::class)
 */
class PowerLog
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Sensors::class, inversedBy="powerLog", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $SensorId;

    /**
     * @ORM\Column(type="datetime")
     */
    private $LastUpdate;

    /**
     * @ORM\Column(type="integer")
     */
    private $current;

    /**
     * @ORM\Column(type="integer")
     */
    private $power;

    /**
     * @ORM\Column(type="integer")
     */
    private $voltage;

    public function __toString() {
        return $this->SensorId->getName();
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSensorId(): ?Sensors
    {
        return $this->SensorId;
    }

    public function setSensorId(Sensors $SensorId): self
    {
        $this->SensorId = $SensorId;

        return $this;
    }

    public function getLastUpdate(): ?\DateTimeInterface
    {
        return $this->LastUpdate;
    }

    public function setLastUpdate(\DateTimeInterface $LastUpdate): self
    {
        $this->LastUpdate = $LastUpdate;

        return $this;
    }

    public function getCurrent(): ?int
    {
        return $this->current;
    }

    public function setCurrent(int $current): self
    {
        $this->current = $current;

        return $this;
    }

    public function getPower(): ?int
    {
        return $this->power;
    }

    public function setPower(int $power): self
    {
        $this->power = $power;

        return $this;
    }

    public function getVoltage(): ?int
    {
        return $this->voltage;
    }

    public function setVoltage(int $voltage): self
    {
        $this->voltage = $voltage;

        return $this;
    }
}
