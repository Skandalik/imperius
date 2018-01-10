<?php
declare(strict_types=1);
namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Traits\IdentityAutoTrait;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
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
     * @ORM\Column(name="created_at", nullable=false)
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
     * @ORM\Column(name="last_run_at", type="datetime", nullable=true)
     */
    private $lastRunAt;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="finished_run_at", type="datetime", nullable=true)
     */
    private $finishedRunAt;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="next_run_at", type="datetime", nullable=true)
     */
    private $nextRunAt;

    /**
     * @var string
     *
     * @ORM\Column(name="relative_date", nullable=false)
     */
    private $relativeDate;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="time", type="time", nullable=true)
     */
    private $time;

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
     * @return DateTime | null
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
     * @return DateTime | null
     */
    public function getTime()
    {
        return $this->time;
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


}