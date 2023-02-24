<?php

namespace App\Entity;

use App\Repository\LightsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LightsRepository::class)
 */
class Lights {

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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $factory;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $model;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $checktime;

    /**
     * @ORM\Column(type="boolean", nullable=true)
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
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $uniqueid;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity=LightBridges::class, inversedBy="lightId")
     * @ORM\JoinColumn(nullable=false)
     */
    private $bridge;

    /**
     * @ORM\OneToMany(targetEntity=LightLog::class, mappedBy="light")
     */
    private $lightLogs;

    /**
     * @ORM\ManyToMany(targetEntity=LightGroups::class, mappedBy="lights")
     */
    private $lightGroups;

    /**
     * @ORM\Column(type="boolean")
     */
    private $hascolor;

    /**
     * @ORM\ManyToMany(targetEntity=Actions::class, mappedBy="Lights")
     */
    private $actions;

    public function __toString() {
        return $this->name . ' @ ' . $this->bridge->getName();
    }

    public function __construct() {
        $this->lightLogs = new ArrayCollection();
        $this->lightGroups = new ArrayCollection();
        $this->actions = new ArrayCollection();
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getInternalId(): ?int {
        return $this->internalId;
    }

    public function setInternalId(int $internalId): self {
        $this->internalId = $internalId;

        return $this;
    }

    public function getFactory(): ?string {
        return $this->factory;
    }

    public function setFactory(?string $factory): self {
        $this->factory = $factory;

        return $this;
    }

    public function getModel(): ?string {
        return $this->model;
    }

    public function setModel(string $model): self {
        $this->model = $model;

        return $this;
    }

    public function getChecktime(): ?\DateTimeInterface {
        return $this->checktime;
    }

    public function setChecktime(?\DateTimeInterface $checktime): self {
        $this->checktime = $checktime;

        return $this;
    }

    public function getStateOn(): ?bool {
        return $this->stateOn;
    }

    public function setStateOn(?bool $stateOn): self {
        $this->stateOn = $stateOn;

        return $this;
    }

    public function getStateBri(): ?int {
        return $this->stateBri;
    }

    public function setStateBri(int $stateBri): self {
        $this->stateBri = $stateBri;

        return $this;
    }

    public function getStateHue(): ?int {
        return $this->stateHue;
    }

    public function setStateHue(int $stateHue): self {
        $this->stateHue = $stateHue;

        return $this;
    }

    public function getStateSat(): ?int {
        return $this->stateSat;
    }

    public function setStateSat(int $stateSat): self {
        $this->stateSat = $stateSat;

        return $this;
    }

    public function getStateXY(): ?array {
        return $this->stateXY;
    }

    public function setStateXY(array $stateXY): self {
        $this->stateXY = $stateXY;

        return $this;
    }

    public function getReachable(): ?bool {
        return $this->reachable;
    }

    public function setReachable(bool $reachable): self {
        $this->reachable = $reachable;

        return $this;
    }

    public function getName(): ?string {
        return $this->name;
    }

    public function setName(string $name): self {
        $this->name = $name;

        return $this;
    }

    public function getUniqueid(): ?string {
        return $this->uniqueid;
    }

    public function setUniqueid(string $uniqueid): self {
        $this->uniqueid = $uniqueid;

        return $this;
    }

    public function getType(): ?string {
        return $this->type;
    }

    public function setType(string $type): self {
        $this->type = $type;

        return $this;
    }

    public function getBridge(): ?LightBridges {
        return $this->bridge;
    }

    public function setBridge(?LightBridges $bridge): self {
        $this->bridge = $bridge;

        return $this;
    }

    /**
     * @return Collection|LightLog[]
     */
    public function getLightLogs(): Collection {
        return $this->lightLogs;
    }

    public function addLightLog(LightLog $lightLog): self {
        if (!$this->lightLogs->contains($lightLog)) {
            $this->lightLogs[] = $lightLog;
            $lightLog->setLight($this);
        }

        return $this;
    }

    public function removeLightLog(LightLog $lightLog): self {
        if ($this->lightLogs->removeElement($lightLog)) {
            // set the owning side to null (unless already changed)
            if ($lightLog->getLight() === $this) {
                $lightLog->setLight(null);
            }
        }

        return $this;
    }

    public function getLightGroups(): ?LightGroups {
        return $this->lightGroups;
    }

    public function setLightGroups(?LightGroups $lightGroups): self {
        $this->lightGroups = $lightGroups;

        return $this;
    }

    public function addLightGroup(LightGroups $lightGroup): self {
        if (!$this->lightGroups->contains($lightGroup)) {
            $this->lightGroups[] = $lightGroup;
            $lightGroup->addLight($this);
        }

        return $this;
    }

    public function removeLightGroup(LightGroups $lightGroup): self {
        if ($this->lightGroups->removeElement($lightGroup)) {
            $lightGroup->removeLight($this);
        }

        return $this;
    }

    public function getHascolor(): ?bool {
        return $this->hascolor;
    }

    public function setHascolor(bool $hascolor): self {
        $this->hascolor = $hascolor;

        return $this;
    }

    /**
     * @return Collection|Actions[]
     */
    public function getActions(): Collection
    {
        return $this->actions;
    }

    public function addAction(Actions $action): self
    {
        if (!$this->actions->contains($action)) {
            $this->actions[] = $action;
            $action->addLight($this);
        }

        return $this;
    }

    public function removeAction(Actions $action): self
    {
        if ($this->actions->removeElement($action)) {
            $action->removeLight($this);
        }

        return $this;
    }

}
