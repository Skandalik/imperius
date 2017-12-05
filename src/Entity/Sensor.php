<?php
declare(strict_types=1);
namespace App\Entity;

use App\Entity\Traits\IdentityAutoTrait;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SensorRepository")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="imp_sensor")
 */
class Sensor
{
    use IdentityAutoTrait;

    /**
     * @var string
     *
     * @ORM\Column(name="uuid", type="guid", nullable=true, unique=true)
     */
    private $uuid;

    /**
     * @ORM\ManyToOne(targetEntity="Room", inversedBy="sensorsInRoom")
     * @ORM\JoinColumn(name="room_id", referencedColumnName="id", nullable=true)
     */
    private $room;

    /**
     * @var string
     *
     * @ORM\Column(name="value_type", type="string", nullable=false)
     */
    private $valueType;

    /**
     * @var bool
     *
     * @ORM\Column(name="switchable", type="boolean", nullable=false)
     */
    private $switchable;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @var string
     *
     * @ORM\Column(name="sensor_ip", type="string", length=50, nullable=false, unique=true)
     */
    private $sensorIp;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="last_data_sent_at", type="datetime", nullable=true)
     */
    private $lastDataSentAt;

    /**
     * @var bool
     *
     * @ORM\Column(name="active", type="boolean", nullable=false)
     */
    private $active;

    /**
     * Sensor constructor.
     */
    public function __construct()
    {
        $this->uuid = $this->createUuid();
        $this->valueType = $this->createValueType();
        $this->sensorIp = $this->createSensorIp();
        $this->switchable = false;
        $this->createdAt = null;
        $this->active = false;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function prePersist()
    {
        $this->setUpdatedAt(new DateTime());

        if (is_null($this->getCreatedAt())) {
            $this->setCreatedAt(new DateTime());
        }
    }

    /**
     * @return string
     */
    public function getUuid(): string
    {
        return $this->uuid;
    }

    /**
     * @param string $uuid
     *
     * @return Sensor
     */
    public function setUuid(string $uuid): Sensor
    {
        $this->uuid = $uuid;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getRoom()
    {
        return $this->room;
    }

    /**
     * @param mixed $room
     *
     * @return Sensor
     */
    public function setRoom($room)
    {
        $this->room = $room;

        return $this;
    }

    /**
     * @return string
     */
    public function getValueType(): string
    {
        return $this->valueType;
    }

    /**
     * @param string $valueType
     *
     * @return Sensor
     */
    public function setValueType(string $valueType): Sensor
    {
        $this->valueType = $valueType;

        return $this;
    }

    /**
     * @return bool
     */
    public function isSwitchable(): bool
    {
        return $this->switchable;
    }

    /**
     * @param bool $switchable
     *
     * @return Sensor
     */
    public function setSwitchable(bool $switchable): Sensor
    {
        $this->switchable = $switchable;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param DateTime $createdAt
     *
     * @return Sensor
     */
    public function setCreatedAt(DateTime $createdAt): Sensor
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param DateTime $updatedAt
     *
     * @return Sensor
     */
    public function setUpdatedAt(DateTime $updatedAt): Sensor
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return string
     */
    public function getSensorIp(): string
    {
        return $this->sensorIp;
    }

    /**
     * @param string $sensorIp
     *
     * @return Sensor
     */
    public function setSensorIp(string $sensorIp): Sensor
    {
        $this->sensorIp = $sensorIp;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getLastDataSentAt(): DateTime
    {
        return $this->lastDataSentAt;
    }

    /**
     * @param DateTime $lastDataSentAt
     *
     * @return Sensor
     */
    public function setLastDataSentAt(DateTime $lastDataSentAt): Sensor
    {
        $this->lastDataSentAt = $lastDataSentAt;

        return $this;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     *
     * @return Sensor
     */
    public function setActive(bool $active): Sensor
    {
        $this->active = $active;

        return $this;
    }

    /**
     * @return string
     */
    private function createValueType(): string
    {
        return "";
    }

    /**
     * @return string
     */
    private function createUuid(): string
    {
        return "";
    }

    /**
     * @return string
     */
    private function createSensorIp(): string
    {
        return "";
    }

}