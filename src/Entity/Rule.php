<?php

namespace App\Entity;

use App\Repository\RuleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RuleRepository::class)
 */
class Rule
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
    private $Name;
    
    /**
     * @ORM\Column(type="boolean")
     */
    private $allTrue;

    /**
     * @ORM\OneToOne(targetEntity=Sensors::class, cascade={"persist", "remove"})
     */
    private $targetSensor;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active;

    /**
     * @ORM\OneToMany(targetEntity=RuleSet::class, mappedBy="Rule", orphanRemoval=true)
     */
    private $ruleSets;


    
    public function __toString() {
        return $this->Name;
    }
    public function __construct()
    {
        $this->ruleSets = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAllTrue(): ?bool
    {
        return $this->allTrue;
    }

    public function setAllTrue(bool $allTrue): self
    {
        $this->allTrue = $allTrue;

        return $this;
    }

    public function getTargetSensor(): ?Sensors
    {
        return $this->targetSensor;
    }

    public function setTargetSensor(?Sensors $targetSensor): self
    {
        $this->targetSensor = $targetSensor;

        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    /**
     * @return Collection|RuleSet[]
     */
    public function getRuleSets(): Collection
    {
        return $this->ruleSets;
    }

    public function addRuleSet(RuleSet $ruleSet): self
    {
        if (!$this->ruleSets->contains($ruleSet)) {
            $this->ruleSets[] = $ruleSet;
            $ruleSet->setRule($this);
        }

        return $this;
    }

    public function removeRuleSet(RuleSet $ruleSet): self
    {
        if ($this->ruleSets->removeElement($ruleSet)) {
            // set the owning side to null (unless already changed)
            if ($ruleSet->getRule() === $this) {
                $ruleSet->setRule(null);
            }
        }

        return $this;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): self
    {
        $this->Name = $Name;

        return $this;
    }
}
