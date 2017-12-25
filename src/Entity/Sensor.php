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
     * @ORM\Column(name="name", type="string", nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="uuid", type="guid", nullable=false, unique=true)
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
     * @var bool
     *
     * @ORM\Column(name="multi_value", type="boolean", nullable=true)
     */
    private $multiValue;

    /**
     * @var int
     *
     * @ORM\Column(name="minimum_value", type="integer", nullable=true)
     */
    private $minimumValue;

    /**
     * @var int
     *
     * @ORM\Column(name="maximum_value", type="integer", nullable=true)
     */
    private $maximumValue;

    /**
     * @var int
     *
     * @ORM\Column(name="value", type="integer", nullable=false)
     */
    private $value;

    /**
     * @var bool
     *
     * @ORM\Column(name="active", type="boolean", nullable=false)
     */
    private $active;

    /**
     * @var string
     *
     * @ORM\Column(name="sensor_ip", type="string", length=50, nullable=false, unique=true)
     */
    private $sensorIp;

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
     * @var DateTime
     *
     * @ORM\Column(name="last_data_sent_at", type="datetime", nullable=true)
     */
    private $lastDataSentAt;

    /**
     * Sensor constructor.
     */
    public function __construct()
    {
        $this
            ->setUuid("")
            ->setValue(0)
            ->setSwitchable(false)
            ->setActive(false)
            ->setSensorIp("")
            ->setCreatedAt(null)
        ;
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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return Sensor
     */
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

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
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * @param string $uuid
     *
     * @return Sensor
     */
    public function setUuid(string $uuid)
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
    public function getValueType()
    {
        return $this->valueType;
    }

    /**
     * @param string $valueType
     *
     * @return Sensor
     */
    public function setValueType(string $valueType)
    {
        $this->valueType = $valueType;

        return $this;
    }

    /**
     * @return bool
     */
    public function isSwitchable()
    {
        return $this->switchable;
    }

    /**
     * @param bool $switchable
     *
     * @return Sensor
     */
    public function setSwitchable(bool $switchable)
    {
        $this->switchable = $switchable;

        return $this;
    }

    /**
     * @return bool
     */
    public function isMultiValue()
    {
        return $this->multiValue;
    }

    /**
     * @param bool $multiValue
     *
     * @return Sensor
     */
    public function setMultiValue(bool $multiValue)
    {
        $this->multiValue = $multiValue;

        return $this;
    }

    /**
     * @return int
     */
    public function getMinimumValue()
    {
        return $this->minimumValue;
    }

    /**
     * @param int $minimumValue
     *
     * @return Sensor
     */
    public function setMinimumValue(int $minimumValue)
    {
        $this->minimumValue = $minimumValue;

        return $this;
    }

    /**
     * @return int
     */
    public function getMaximumValue()
    {
        return $this->maximumValue;
    }

    /**
     * @param int $maximumValue
     *
     * @return Sensor
     */
    public function setMaximumValue(int $maximumValue)
    {
        $this->maximumValue = $maximumValue;

        return $this;
    }

    /**
     * @return int
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param int $value
     *
     * @return Sensor
     */
    public function setValue(int $value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->active;
    }

    /**
     * @param bool $active
     *
     * @return Sensor
     */
    public function setActive(bool $active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * @return string
     */
    public function getSensorIp()
    {
        return $this->sensorIp;
    }

    /**
     * @param string $sensorIp
     *
     * @return Sensor
     */
    public function setSensorIp(string $sensorIp)
    {
        $this->sensorIp = $sensorIp;

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
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param DateTime $updatedAt
     *
     * @return Sensor
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getLastDataSentAt()
    {
        return $this->lastDataSentAt;
    }

    /**
     * @param DateTime $lastDataSentAt
     *
     * @return Sensor
     */
    public function setLastDataSentAt($lastDataSentAt)
    {
        $this->lastDataSentAt = $lastDataSentAt;

        return $this;
    }

}