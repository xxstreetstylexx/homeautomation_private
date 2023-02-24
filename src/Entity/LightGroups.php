<?php

namespace App\Entity;

use App\Repository\LightGroupsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LightGroupsRepository::class)
 */
class LightGroups
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $internalId;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\Column(type="boolean")
     */
    private $stateAll;

    /**
     * @ORM\Column(type="boolean")
     */
    private $stateAny;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $class;

    /**
     * @ORM\ManyToOne(targetEntity=LightBridges::class, inversedBy="lightGroups")
     * @ORM\JoinColumn(nullable=false)
     */
    private $bridge;

    /**
     * @ORM\ManyToMany(targetEntity=Lights::class, inversedBy="lightGroups")
     */
    private $lights;

    /**
     * @ORM\OneToMany(targetEntity=Scenes::class, mappedBy="GroupId", orphanRemoval=true)
     */
    private $scenes;

     public function __construct()
    {
        $this->lights = new ArrayCollection();
        $this->scenes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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

    public function getStateAll(): ?bool
    {
        return $this->stateAll;
    }

    public function setStateAll(bool $stateAll): self
    {
        $this->stateAll = $stateAll;

        return $this;
    }

    public function getStateAny(): ?bool
    {
        return $this->stateAny;
    }

    public function setStateAny(bool $stateAny): self
    {
        $this->stateAny = $stateAny;

        return $this;
    }

    public function getClass(): ?string
    {
        return $this->class;
    }

    public function setClass(string $class): self
    {
        $this->class = $class;

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

    /**
     * @return Collection|Lights[]
     */
    public function getLights(): Collection
    {
        return $this->lights;
    }

    public function addLight(Lights $light): self
    {
        if (!$this->lights->contains($light)) {
            $this->lights[] = $light;
        }

        return $this;
    }

    public function removeLight(Lights $light): self
    {
        $this->lights->removeElement($light);

        return $this;
    }

    /**
     * @return Collection|Scenes[]
     */
    public function getScenes(): Collection
    {
        return $this->scenes;
    }

    public function addScene(Scenes $scene): self
    {
        if (!$this->scenes->contains($scene)) {
            $this->scenes[] = $scene;
            $scene->setGroupId($this);
        }

        return $this;
    }

    public function removeScene(Scenes $scene): self
    {
        if ($this->scenes->removeElement($scene)) {
            // set the owning side to null (unless already changed)
            if ($scene->getGroupId() === $this) {
                $scene->setGroupId(null);
            }
        }

        return $this;
    }

}
