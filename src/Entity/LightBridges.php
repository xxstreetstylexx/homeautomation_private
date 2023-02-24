<?php

namespace App\Entity;

use App\Repository\LightBridgesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LightBridgesRepository::class)
 */
class LightBridges
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
    private $ip;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $account;

    /**
     * @ORM\OneToMany(targetEntity=Lights::class, mappedBy="bridge")
     */
    private $lightId;

    /**
     * @ORM\OneToMany(targetEntity=LightGroups::class, mappedBy="bridge")
     */
    private $lightGroups;

    /**
     * @ORM\OneToMany(targetEntity=Sensors::class, mappedBy="bridge", orphanRemoval=true)
     */
    private $sensors;
    
    public function __toString() {
        return $this->name;
    }

    public function __construct()
    {
        $this->lightId = new ArrayCollection();
        $this->lightGroups = new ArrayCollection();
        $this->sensors = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIp(): ?string
    {
        return $this->ip;
    }

    public function setIp(string $ip): self
    {
        $this->ip = $ip;

        return $this;
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

    public function getAccount(): ?string
    {
        return $this->account;
    }

    public function setAccount(string $account): self
    {
        $this->account = $account;

        return $this;
    }

    /**
     * @return Collection|Lights[]
     */
    public function getLightId(): Collection
    {
        return $this->lightId;
    }

    public function addLightId(Lights $lightId): self
    {
        if (!$this->lightId->contains($lightId)) {
            $this->lightId[] = $lightId;
            $lightId->setBridge($this);
        }

        return $this;
    }

    public function removeLightId(Lights $lightId): self
    {
        if ($this->lightId->removeElement($lightId)) {
            // set the owning side to null (unless already changed)
            if ($lightId->getBridge() === $this) {
                $lightId->setBridge(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|LightGroups[]
     */
    public function getLightGroups(): Collection
    {
        return $this->lightGroups;
    }

    public function addLightGroup(LightGroups $lightGroup): self
    {
        if (!$this->lightGroups->contains($lightGroup)) {
            $this->lightGroups[] = $lightGroup;
            $lightGroup->setBridge($this);
        }

        return $this;
    }

    public function removeLightGroup(LightGroups $lightGroup): self
    {
        if ($this->lightGroups->removeElement($lightGroup)) {
            // set the owning side to null (unless already changed)
            if ($lightGroup->getBridge() === $this) {
                $lightGroup->setBridge(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Sensors[]
     */
    public function getSensors(): Collection
    {
        return $this->sensors;
    }

    public function addSensor(Sensors $sensor): self
    {
        if (!$this->sensors->contains($sensor)) {
            $this->sensors[] = $sensor;
            $sensor->setBridge($this);
        }

        return $this;
    }

    public function removeSensor(Sensors $sensor): self
    {
        if ($this->sensors->removeElement($sensor)) {
            // set the owning side to null (unless already changed)
            if ($sensor->getBridge() === $this) {
                $sensor->setBridge(null);
            }
        }

        return $this;
    }
}
