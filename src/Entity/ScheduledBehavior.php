<?php
declare(strict_types=1);
namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Behavior\Abstraction\BehaviorInterface;
use App\Entity\Traits\IdentityAutoTrait;
use App\Util\DateSupplier\DateSupplier;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use function is_null;

/**
 * @ApiResource(
 *     attributes={
 *      "normalization_context"={"groups"={"scheduled", "common"}},
 *      "denormalization_context"={"groups"={"scheduled", "common"}},
 *     })
 * @ORM\Entity(repositoryClass="App\Repository\ScheduledBehaviorRepository")
 * @ORM\Table(name="imp_scheduled_behavior")
 * @ORM\HasLifecycleCallbacks()
 */
class ScheduledBehavior implements BehaviorInterface
{
    use IdentityAutoTrait;

    /**
     * @var Sensor
     *
     * @ORM\ManyToOne(targetEntity="Sensor", inversedBy="scheduledBehaviors")
     * @ORM\JoinColumn(name="sensor_id", referencedColumnName="id", nullable=false)
     * @Groups({"scheduled"})
     */
    private $sensor;

    /**
     * @var string
     *
     * @ORM\Column(type="sensor_actions_enum", nullable=false)
     * @Groups({"scheduled"})
     */
    private $action;

    /**
     * @var int
     *
     * @ORM\Column(name="action_argument", type="integer", nullable=true)
     * @Groups({"scheduled"})
     */
    private $actionArgument;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     * @Groups({"scheduled"})
     */
    private $createdAt;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     * @Groups({"behavior"})
     */
    private $updatedAt;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="last_run_at", type="datetime", nullable=true)
     * @Groups({"scheduled"})
     */
    private $lastRunAt;

    /**
     * @var bool
     *
     * @ORM\Column(name="finished", type="boolean", nullable=true, options={"default": false})
     * @Groups({"scheduled"})
     */
    private $finished;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="next_run_at", type="datetime", nullable=true)
     * @Groups({"scheduled"})
     */
    private $nextRunAt;

    /**
     * @var string
     *
     * @ORM\Column(name="relative_date", type="text", nullable=false)
     * @Groups({"scheduled"})
     */
    private $relativeDate;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="time", type="time", nullable=true)
     * @Groups({"scheduled"})
     */
    private $time;

    /**
     * @var bool
     *
     * @ORM\Column(name="repeatable", type="boolean", nullable=false)
     * @Groups({"scheduled"})
     */
    private $repeatable;

    /**
     * @ORM\PrePersist()
     *
     * @ORM\PreUpdate()
     */
    public function prePersist()
    {
        $this->setFinished(false);
        $this->setUpdatedAt(new DateTime());

        if (is_null($this->createdAt)) {
            $this->setCreatedAt(new DateTime());
        }
        $date = new DateSupplier();
        $this->setNextRunAt($date->convertRelativeDate($this->isRepeatable(), $this->getRelativeDate(), $this->getTime(), $this->getLastRunAt()));
    }

    /**
     * ScheduledBehavior constructor.
     */
    public function __construct()
    {
        $this->setCreatedAt(new DateTime());
        $this->setRepeatable(false);
        $this->setFinished(false);
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
     * @return bool
     */
    public function isFinished()
    {
        return $this->finished;
    }

    /**
     * @param bool $finished
     *
     * @return ScheduledBehavior
     */
    public function setFinished($finished)
    {
        $this->finished = $finished;

        return $this;
    }

    /**
     * @return string | null
     */
    public function getNextRunAt()
    {
        return $this->nextRunAt;
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
        return is_null($this->time) ? '' : date_format($this->time, 'H:i:s');
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
     * @return bool
     */
    public function isRepeatable(): bool
    {
        return $this->repeatable;
    }

    /**
     * @param bool $repeatable
     */
    public function setRepeatable(bool $repeatable)
    {
        $this->repeatable = $repeatable;
    }

    /**
     * @return Sensor
     */
    public function getActionSensor(): Sensor
    {
        return $this->sensor;
    }

    /**
     * @param Sensor $sensor
     *
     * @return BehaviorInterface
     */
    public function setActionSensor(Sensor $sensor): BehaviorInterface
    {
        $this->sensor = $sensor;

        return $this;
    }

    /**
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * @param string $action
     *
     * @return $this
     */
    public function setAction(string $action)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * @return int | null
     */
    public function getActionArgument()
    {
        return $this->actionArgument;
    }

    /**
     * @param int | null $argument
     *
     * @return $this
     */
    public function setActionArgument($argument)
    {
        $this->actionArgument = $argument;

        return $this;
    }
}