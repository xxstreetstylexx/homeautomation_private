<?php

namespace App\Entity;

use App\Repository\DeviceSettingRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DeviceSettingRepository::class)
 */
class DeviceSetting
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=Device::class, inversedBy="deviceSetting", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $deviceId;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $config = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDeviceId(): ?Device
    {
        return $this->deviceId;
    }

    public function setDeviceId(Device $deviceId): self
    {
        $this->deviceId = $deviceId;

        return $this;
    }

    public function getConfig(): ?array
    {
        return $this->config;
    }

    public function setConfig(?array $config): self
    {
        $this->config = $config;

        return $this;
    }
}
