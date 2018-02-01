<?php
declare(strict_types=1);
namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Entity\Traits\IdentityAutoTrait;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     attributes={
 *      "normalization_context"={"groups"={"sensor", "common"}},
 *      "denormalization_context"={"groups"={"sensor", "manual", "scheduled", "common"}},
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
     * @Groups({"sensor", "manual", "scheduled"})
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="uuid", type="string", length=50, nullable=false, unique=true)
     * @Groups({"sensor", "manual", "scheduled"})
     */
    private $uuid;

    /**
     * @ORM\ManyToOne(targetEntity="Room", inversedBy="sensorsInRoom")
     * @ORM\JoinColumn(name="room_id", referencedColumnName="id", nullable=true)
     *
     * @Groups({"sensor", "manual", "scheduled"})
     */
    private $room;

    /**
     * @var bool
     *
     * @ORM\Column(name="fetchable", type="boolean", nullable=false)
     * @Groups({"sensor", "manual", "scheduled"})
     */
    private $fetchable;

    /**
     * @var bool
     *
     * @ORM\Column(name="switchable", type="boolean", nullable=false)
     * @Groups({"sensor", "manual", "scheduled"})
     */
    private $switchable;

    /**
     * @var bool
     *
     * @ORM\Column(name="adjustable", type="boolean", nullable=false)
     * @Groups({"sensor", "manual", "scheduled"})
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
     * @var string
     *
     * @ORM\Column(name="data_type", type="string", nullable=false)
     * @Groups({"sensor"})
     */
    private $dataType;

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
     * @ORM\OneToMany(targetEntity="ManualBehavior", mappedBy="sensor", orphanRemoval=true)
     * @ApiSubresource()
     */
    private $manualBehaviors;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="ScheduledBehavior", mappedBy="sensor", orphanRemoval=true)
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
            ->setDataType("")
        ;
        $this->manualBehaviors = new ArrayCollection();
        $this->scheduledBehaviors = new ArrayCollection();
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

        if (empty($this->getDataType())) {
            $this->setDataType('none');
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
     * @return ArrayCollection | ManualBehavior[]
     */
    public function getManualBehaviors()
    {
        return $this->manualBehaviors;
    }

    /**
     * @param ArrayCollection | ManualBehavior[] $manualBehaviors
     */
    public function setManualBehaviors($manualBehaviors)
    {
        $this->manualBehaviors = $manualBehaviors;
    }

    /**
     * @param ManualBehavior $behavior
     *
     * @return Sensor
     */
    public function addManualBehavior(ManualBehavior $behavior)
    {
        if (!$this->manualBehaviors->contains($behavior)) {
            return $this;
        }
        $this->manualBehaviors[] = $behavior;
        $behavior->setSensor($this);

        return $this;
    }

    /**
     * @param ManualBehavior $behavior
     */
    public function removeManualBehavior(ManualBehavior $behavior)
    {
        $this->manualBehaviors->removeElement($behavior);
        $behavior->setSensor(null);
    }

    /**
     * @return ArrayCollection | ScheduledBehavior[]
     */
    public function getScheduledBehaviors()
    {
        return $this->scheduledBehaviors;
    }

    /**
     * @param ArrayCollection | ScheduledBehavior[] $scheduledBehaviors
     */
    public function setScheduledBehaviors($scheduledBehaviors)
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

    /**
     * @return string
     */
    public function getDataType(): string
    {
        return $this->dataType;
    }

    /**
     * @param string $dataType
     */
    public function setDataType(string $dataType)
    {
        $this->dataType = $dataType;
    }
}