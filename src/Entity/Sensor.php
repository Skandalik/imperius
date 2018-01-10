<?php
declare(strict_types=1);
namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Entity\Traits\IdentityAutoTrait;
use App\Type\SensorStateEnumType;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;

/**
 * @ApiResource(
 *     attributes={
 *      "normalization_context"={"groups"={"sensor"}},
 *      "denormalization_context"={"groups"={"sensor", "behavior"}},
 *      "force_eager"=false
 *     })
 * @ORM\Entity(repositoryClass="App\Repository\SensorRepository")
 * @ORM\Table(name="imp_sensor")
 * @ORM\HasLifecycleCallbacks
 */
class Sensor
{
    use IdentityAutoTrait;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", nullable=true)
     * @Groups({"sensor", "behavior"})
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="uuid", type="string", length=50, nullable=false, unique=true)
     * @Groups({"sensor", "behavior"})
     */
    private $uuid;

    /**
     * @ORM\ManyToOne(targetEntity="Room", inversedBy="sensorsInRoom", cascade={"persist", "refresh"})
     * @ORM\JoinColumn(name="room_id", referencedColumnName="id", nullable=true)
     *
     * @Groups({"sensor", "behavior"})
     */
    private $room;

    /**
     * @var bool
     *
     * @ORM\Column(name="fetchable", type="boolean", nullable=false)
     * @Groups({"sensor", "behavior"})
     */
    private $fetchable;

    /**
     * @var bool
     *
     * @ORM\Column(name="switchable", type="boolean", nullable=false)
     * @Groups({"sensor", "behavior"})
     */
    private $switchable;

    /**
     * @var bool
     *
     * @ORM\Column(name="adjustable", type="boolean", nullable=false)
     * @Groups({"sensor", "behavior"})
     */
    private $adjustable;

    /**
     * @var int
     *
     * @ORM\Column(name="minimum_value", type="integer", nullable=true)
     * @Groups({"sensor"})
     */
    private $minimumValue;

    /**
     * @var int
     *
     * @ORM\Column(name="maximum_value", type="integer", nullable=true)
     * @Groups({"sensor"})
     */
    private $maximumValue;

    /**
     * @var int
     *
     * @ORM\Column(name="status", type="integer", nullable=false)
     * @Groups({"sensor"})
     */
    private $status;

    /**
     * @var bool
     *
     * @ORM\Column(name="active", type="boolean", nullable=false)
     * @Groups({"sensor"})
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
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Behavior", mappedBy="sourceSensor", orphanRemoval=true, cascade={"persist", "refresh"})
     * @Groups({"sensor"})
     * @ApiSubresource()
     */
    private $behaviors;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="ScheduledBehavior", mappedBy="sensor", orphanRemoval=true, cascade={"persist", "refresh"})
     * @Groups({"sensor"})
     * @ApiSubresource()
     */
    private $scheduledBehaviors;

    /**
     * Sensor constructor.
     */
    public function __construct()
    {
        $this
            ->setUuid("")
            ->setStatus(0)
            ->setFetchable(false)
            ->setSwitchable(false)
            ->setAdjustable(false)
            ->setActive(false)
            ->setSensorIp("")
            ->setCreatedAt(null)
        ;
        $this->behaviors = new ArrayCollection();
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
     * @return int
     */
    public function getMinimumValue()
    {
        return $this->minimumValue;
    }

    /**
     * @param int | null $minimumValue
     *
     * @return Sensor
     */
    public function setMinimumValue($minimumValue)
    {
        $this->minimumValue = $minimumValue;

        return $this;
    }

    /**
     * @return int | null
     */
    public function getMaximumValue()
    {
        return $this->maximumValue;
    }

    /**
     * @param int | null $maximumValue
     *
     * @return Sensor
     */
    public function setMaximumValue($maximumValue)
    {
        $this->maximumValue = $maximumValue;

        return $this;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param int $status
     *
     * @return Sensor
     */
    public function setStatus(int $status)
    {
        $this->status = $status;

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

    /**
     * @return bool
     */
    public function isAdjustable()
    {
        return $this->adjustable;
    }

    /**
     * @param bool $adjustable
     *
     * @return Sensor
     */
    public function setAdjustable(bool $adjustable)
    {
        $this->adjustable = $adjustable;

        return $this;
    }

    /**
     * @return bool
     */
    public function isFetchable(): bool
    {
        return $this->fetchable;
    }

    /**
     * @param $fetchable
     *
     * @return Sensor
     */
    public function setFetchable($fetchable): Sensor
    {
        $this->fetchable = $fetchable;

        return $this;
    }

    /**
     * @return bool
     */
    public function getActive(): bool
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
     * @return ArrayCollection
     */
    public function getBehaviors()
    {
        return $this->behaviors;
    }

    /**
     * @param ArrayCollection $behaviors
     */
    public function setBehaviors(ArrayCollection $behaviors)
    {
        $this->behaviors = $behaviors;
    }

    /**
     * @param Behavior $behavior
     *
     * @return Sensor
     */
    public function addBehavior(Behavior $behavior)
    {
        if (!$this->behaviors->contains($behavior)) {
            return $this;
        }
        $this->behaviors[] = $behavior;
        $behavior->setSourceSensor($this);

        return $this;
    }

    /**
     * @param Behavior $behavior
     */
    public function removeBehavior(Behavior $behavior)
    {
        $this->behaviors->removeElement($behavior);
        $behavior->setSourceSensor(null);
    }
    /**
     * @return ArrayCollection
     */
    public function getScheduledBehaviors()
    {
        return $this->scheduledBehaviors;
    }

    /**
     * @param ArrayCollection $scheduledBehaviors
     */
    public function setScheduledBehaviors(ArrayCollection $scheduledBehaviors)
    {
        $this->scheduledBehaviors = $scheduledBehaviors;
    }

    /**
     * @param ScheduledBehavior $scheduledBehavior
     *
     * @return Sensor
     */
    public function addScheduledBehavior(ScheduledBehavior $scheduledBehavior)
    {
        if (!$this->scheduledBehaviors->contains($scheduledBehavior)) {
            return $this;
        }
        $this->scheduledBehaviors[] = $scheduledBehavior;
        $scheduledBehavior->setSensor($this);

        return $this;
    }

    /**
     * @param ScheduledBehavior $scheduledBehavior
     */
    public function removeScheduledBehavior(ScheduledBehavior $scheduledBehavior)
    {
        $this->scheduledBehaviors->removeElement($scheduledBehavior);
        $scheduledBehavior->setSensor(null);
    }

    /**
     * @return $this
     */
    public function updateTimestamp()
    {
        $this->setUpdatedAt(new DateTime());

        return $this;
    }

    public function __toString()
    {
        return $this->getUuid();
    }
}