<?php

namespace App\Entity;

use App\Repository\DeviceRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DeviceRepository::class)
 */
class Device
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
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $lastseen;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $location;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\OneToOne(targetEntity=DeviceSetting::class, mappedBy="deviceId", cascade={"persist", "remove"})
     */
    private $deviceSetting;

    /**
     * @ORM\OneToOne(targetEntity=Messages::class, mappedBy="sender", cascade={"persist", "remove"})
     */
    private $sendMessages;

    /**
     * @ORM\OneToOne(targetEntity=Messages::class, mappedBy="reciever", cascade={"persist", "remove"})
     */
    private $revieveMessages;

    public function __toString() 
    {
        return $this->name;
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

    public function getLastseen(): ?\DateTimeInterface
    {
        return $this->lastseen;
    }

    public function setLastseen(?\DateTimeInterface $lastseen): self
    {
        $this->lastseen = $lastseen;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(?string $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDeviceSetting(): ?DeviceSetting
    {
        return $this->deviceSetting;
    }

    public function setDeviceSetting(DeviceSetting $deviceSetting): self
    {
        // set the owning side of the relation if necessary
        if ($deviceSetting->getDeviceId() !== $this) {
            $deviceSetting->setDeviceId($this);
        }

        $this->deviceSetting = $deviceSetting;

        return $this;
    }

    public function getSendMessages(): ?Messages
    {
        return $this->sendMessages;
    }

    public function setSendMessages(?Messages $sendMessages): self
    {
        // unset the owning side of the relation if necessary
        if ($sendMessages === null && $this->sendMessages !== null) {
            $this->sendMessages->setSender(null);
        }

        // set the owning side of the relation if necessary
        if ($sendMessages !== null && $sendMessages->getSender() !== $this) {
            $sendMessages->setSender($this);
        }

        $this->sendMessages = $sendMessages;

        return $this;
    }

    public function getRevieveMessages(): ?Messages
    {
        return $this->revieveMessages;
    }

    public function setRevieveMessages(Messages $revieveMessages): self
    {
        // set the owning side of the relation if necessary
        if ($revieveMessages->getReciever() !== $this) {
            $revieveMessages->setReciever($this);
        }

        $this->revieveMessages = $revieveMessages;

        return $this;
    }
}
