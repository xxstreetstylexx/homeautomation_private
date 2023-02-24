<?php

namespace App\Entity;

use App\Repository\MessagesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MessagesRepository::class)
 */
class Messages
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=Device::class, inversedBy="sendMessages", cascade={"persist", "remove"})
     */
    private $sender;

    /**
     * @ORM\OneToOne(targetEntity=Device::class, inversedBy="revieveMessages", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $reciever;

    /**
     * @ORM\Column(type="boolean")
     */
    private $opened;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSender(): ?Device
    {
        return $this->sender;
    }

    public function setSender(?Device $sender): self
    {
        $this->sender = $sender;

        return $this;
    }

    public function getReciever(): ?Device
    {
        return $this->reciever;
    }

    public function setReciever(Device $reciever): self
    {
        $this->reciever = $reciever;

        return $this;
    }

    public function getOpened(): ?bool
    {
        return $this->opened;
    }

    public function setOpened(bool $opened): self
    {
        $this->opened = $opened;

        return $this;
    }
}
