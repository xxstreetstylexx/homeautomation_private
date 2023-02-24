<?php

namespace App\Entity;

use App\Repository\SensorsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SensorsRepository::class)
 */
class Sensors
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=LightBridges::class, inversedBy="sensors")
     * @ORM\JoinColumn(nullable=false)
     */
    private $bridge;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\Column(type="integer")
     */
    private $internalId;

    /**
     * @ORM\Column(type="boolean")
     */
    private $reachable;
    
    /**
     * @ORM\Column(type="boolean")
     */
    private $virtual;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $uniqueid;

    /**
     * @ORM\Column(type="json")
     */
    private $state = [];

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $battery;

    /**
     * @ORM\Column(type="datetime")
     */
    private $checktime;

    /**
     * @ORM\OneToMany(targetEntity=PowerLog::class, mappedBy="SensorId", cascade={"persist", "remove"})
     */
    private $powerLog;
    
    public function __toString() 
    {
        return $this->name .' - '. $this->type .' @ '. $this->bridge->getName();        
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

    public function getBridge(): ?LightBridges
    {
        return $this->bridge;
    }

    public function setBridge(?LightBridges $bridge): self
    {
        $this->bridge = $bridge;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getInternalId(): ?int
    {
        return $this->internalId;
    }

    public function setInternalId(int $internalId): self
    {
        $this->internalId = $internalId;

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
    
    public function getVirtual(): ?bool
    {
        return $this->virtual;
    }

    public function setVirtual(bool $virtual): self
    {
        $this->virtual = $virtual;

        return $this;
    }

    public function getUniqueid(): ?string
    {
        return $this->uniqueid;
    }

    public function setUniqueid(string $uniqueid): self
    {
        $this->uniqueid = $uniqueid;

        return $this;
    }

    public function getState(): ?array
    {
        return $this->state;
    }

    public function setState(array $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getBattery(): ?int
    {
        return $this->battery;
    }

    public function setBattery(?int $battery): self
    {
        $this->battery = $battery;

        return $this;
    }

    public function getChecktime(): ?\DateTimeInterface
    {
        return $this->checktime;
    }

    public function setChecktime(\DateTimeInterface $checktime): self
    {
        $this->checktime = $checktime;

        return $this;
    }

    public function getPowerLog(): ?PowerLog
    {
        return $this->powerLog;
    }

    public function setPowerLog(PowerLog $powerLog): self
    {
        // set the owning side of the relation if necessary
        if ($powerLog->getSensorId() !== $this) {
            $powerLog->setSensorId($this);
        }

        $this->powerLog = $powerLog;

        return $this;
    }
}
