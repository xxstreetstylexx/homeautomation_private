<?php

namespace App\Entity;

use App\Repository\ScenesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ScenesRepository::class)
 */
class Scenes
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=LightBridges::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $Bridge;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Name;

    /**
     * @ORM\Column(type="integer")
     */
    private $SceneId;

    /**
     * @ORM\ManyToOne(targetEntity=LightGroups::class, inversedBy="scenes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $GroupId;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBridge(): ?LightBridges
    {
        return $this->Bridge;
    }

    public function setBridge(?LightBridges $Bridge): self
    {
        $this->Bridge = $Bridge;

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

    public function getSceneId(): ?int
    {
        return $this->SceneId;
    }

    public function setSceneId(int $SceneId): self
    {
        $this->SceneId = $SceneId;

        return $this;
    }

    public function getGroupId(): ?LightGroups
    {
        return $this->GroupId;
    }

    public function setGroupId(?LightGroups $GroupId): self
    {
        $this->GroupId = $GroupId;

        return $this;
    }

}
