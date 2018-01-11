<?php
declare(strict_types=1);
namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Traits\IdentityAutoTrait;
use App\Util\DateSupplier\DateSupplier;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use function is_null;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     attributes={
 *      "normalization_context"={"groups"={"schedule"}},
 *      "denormalization_context"={"groups"={"schedule"}},
 *     })
 * @ORM\Entity(repositoryClass="App\Repository\ScheduledBehaviorRepository")
 * @ORM\Table(name="imp_scheduled_behavior")
 * @ORM\HasLifecycleCallbacks()
 */
class ScheduledBehavior
{
    use IdentityAutoTrait;

    /**
     * @var Sensor
     *
     * @ORM\ManyToOne(targetEntity="Sensor", inversedBy="scheduledBehaviors", cascade={"persist", "refresh"})
     * @ORM\JoinColumn(name="sensor_id", referencedColumnName="id", nullable=false)
     * @Groups({"schedule"})
     */
    private $sensor;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     * @Groups({"schedule"})
     */
    private $createdAt;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     * @Groups({"schedule"})
     */
    private $updatedAt;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="last_run_at", type="datetime", nullable=true)
     * @Groups({"schedule"})
     */
    private $lastRunAt;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="finished_run_at", type="datetime", nullable=true)
     * @Groups({"schedule"})
     */
    private $finishedRunAt;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="next_run_at", type="datetime", nullable=true)
     * @Groups({"schedule"})
     */
    private $nextRunAt;

    /**
     * @var string
     *
     * @ORM\Column(name="relative_date", type="text", nullable=false)
     * @Groups({"schedule"})
     */
    private $relativeDate;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="time", type="time", nullable=false)
     * @Groups({"schedule"})
     */
    private $time;

    /**
     * @var string
     *
     * @ORM\Column(name="scheduled_action", type="sensor_actions_enum", nullable=false)
     * @Groups({"behavior"})
     */
    private $scheduledAction;

    /**
     * @var int
     *
     * @ORM\Column(name="scheduled_action_argument", type="integer", nullable=true)
     * @Groups({"behavior"})
     */
    private $scheduledActionArgument;

    /**
     * @ORM\PrePersist()
     *
     * @ORM\PreUpdate()
     */
    public function prePersist()
    {
        $this->setUpdatedAt(new DateTime());

        if (is_null($this->createdAt)) {
            $this->setCreatedAt(new DateTime());
        }
        $date = new DateSupplier();
        $this->setNextRunAt($date->convertRelativeDate($this->getRelativeDate(), $this->getTime()));
    }

    /**
     * ScheduledBehavior constructor.
     */
    public function __construct()
    {
        $this->setCreatedAt(new DateTime());
    }

    /**
     * @return Sensor
     */
    public function getSensor(): Sensor
    {
        return $this->sensor;
    }

    /**
     * @param Sensor $sensor
     *
     * @return ScheduledBehavior
     */
    public function setSensor(Sensor $sensor)
    {
        $this->sensor = $sensor;

        return $this;
    }

    /**
     * @return DateTime | null
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param DateTime | null $createdAt
     *
     * @return ScheduledBehavior
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return DateTime | null
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param DateTime | null $updatedAt
     *
     * @return ScheduledBehavior
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return DateTime | null
     */
    public function getLastRunAt()
    {
        return $this->lastRunAt;
    }

    /**
     * @param DateTime | null $lastRunAt
     *
     * @return ScheduledBehavior
     */
    public function setLastRunAt($lastRunAt)
    {
        $this->lastRunAt = $lastRunAt;

        return $this;
    }

    /**
     * @return DateTime | null
     */
    public function getFinishedRunAt()
    {
        return $this->finishedRunAt;
    }

    /**
     * @param DateTime | null $finishedRunAt
     *
     * @return ScheduledBehavior
     */
    public function setFinishedRunAt($finishedRunAt)
    {
        $this->finishedRunAt = $finishedRunAt;

        return $this;
    }

    /**
     * @return string | null
     */
    public function getNextRunAt()
    {
        return $this->nextRunAt ? date_format($this->nextRunAt, 'Y-m-d H:i') : null;
    }

    /**
     * @param DateTime | null $nextRunAt
     *
     * @return ScheduledBehavior
     */
    public function setNextRunAt($nextRunAt)
    {
        $this->nextRunAt = $nextRunAt;

        return $this;
    }

    /**
     * @return string | null
     */
    public function getRelativeDate()
    {
        return $this->relativeDate;
    }

    /**
     * @param string | null $relativeDate
     *
     * @return ScheduledBehavior
     */
    public function setRelativeDate($relativeDate)
    {
        $this->relativeDate = $relativeDate;

        return $this;
    }

    /**
     * @return string | null
     */
    public function getTime()
    {
        return date_format($this->time, 'H:i:s');
    }

    /**
     * @param DateTime | null $time
     *
     * @return ScheduledBehavior
     */
    public function setTime($time)
    {
        $this->time = $time;

        return $this;
    }

    /**
     * @return string
     */
    public function getScheduledAction(): string
    {
        return $this->scheduledAction;
    }

    /**
     * @param string $scheduledAction
     *
     * @return ScheduledBehavior
     */
    public function setScheduledAction(string $scheduledAction)
    {
        $this->scheduledAction = $scheduledAction;

        return $this;
    }

    /**
     * @return null | int
     */
    public function getScheduledActionArgument()
    {
        return $this->scheduledActionArgument;
    }

    /**
     * @param int | null $scheduledActionArgument
     *
     * @return ScheduledBehavior
     */
    public function setScheduledActionArgument($scheduledActionArgument)
    {
        $this->scheduledActionArgument = $scheduledActionArgument;

        return $this;
    }

}