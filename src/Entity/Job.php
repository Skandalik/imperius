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
 *      "normalization_context"={"groups"={"job", "common"}},
 *      "denormalization_context"={"groups"={"job", "common"}},
 *      "force_eager"=false
 *     })
 * @ORM\Entity(repositoryClass="App\Repository\JobRepository")
 * @ORM\Table(name="imp_job")
 * @ORM\HasLifecycleCallbacks()
 */
class Job
{
    use IdentityAutoTrait;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", nullable=false)
     * @Groups({"job"})
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="command", type="string", nullable=false)
     * @Groups({"job"})
     */
    private $command;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="last_run_at", type="datetime", nullable=true)
     */
    private $lastRunAt;

    /**
     * @var string
     *
     * @ORM\Column(name="time", type="string", nullable=false)
     * @Groups({"job"})
     */
    private $time;

    /**
     * @var bool
     *
     * @ORM\Column(name="running", type="boolean", nullable=true)
     * @Groups({"job"})
     */
    private $running;

    /**
     * @var bool
     *
     * @ORM\Column(name="error", type="boolean", nullable=true)
     * @Groups({"job"})
     */
    private $error;

    /**
     * @var bool
     *
     * @ORM\Column(name="repeatable", type="boolean", nullable=false)
     * @Groups({"job"})
     */
    private $repeatable;

    /**
     * @var bool
     *
     * @ORM\Column(name="finished", type="boolean", nullable=true)
     * @Groups({"job"})
     */
    private $finished;

    /**
     * @var bool
     *
     * @ORM\Column(name="immediate_rerun", type="boolean", nullable=false)
     * @Groups({"job"})
     */
    private $immediateRerun;

    /**
     * @var int
     *
     * @ORM\Column(name="job_pid", type="integer", nullable=true)
     * @Groups({"job"})
     */
    private $jobPid;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getCommand(): string
    {
        return $this->command;
    }

    /**
     * @param string $command
     */
    public function setCommand(string $command)
    {
        $this->command = $command;
    }

    /**
     * @return string
     */
    public function getTime(): string
    {
        return $this->time;
    }

    /**
     * @param string $time
     */
    public function setTime(string $time)
    {
        $this->time = $time;
    }

    /**
     * @return bool | null
     */
    public function isRunning()
    {
        return $this->running;
    }

    /**
     * @param $running
     *
     * @return Job
     */
    public function setRunning($running)
    {
        $this->running = $running;

        return $this;
    }

    /**
     * @return bool | null
     */
    public function isError()
    {
        return $this->error;
    }

    /**
     * @param $error
     *
     * @return Job
     */
    public function setError($error)
    {
        $this->error = $error;

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
     * @return bool | null
     */
    public function isFinished()
    {
        return $this->finished;
    }

    /**
     * @param $finished
     *
     * @return Job
     */
    public function setFinished($finished)
    {
        $this->finished = $finished;

        return $this;
    }

    /**
     * @return bool
     */
    public function isImmediateRerun(): bool
    {
        return $this->immediateRerun;
    }

    /**
     * @param bool $immediateRerun
     */
    public function setImmediateRerun(bool $immediateRerun)
    {
        $this->immediateRerun = $immediateRerun;
    }

    /**
     * @return DateTime | null
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param $createdAt
     *
     * @return Job
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getLastRunAt(): DateTime
    {
        return $this->lastRunAt;
    }

    /**
     * @param $lastRunAt
     *
     * @return Job
     */
    public function setLastRunAt($lastRunAt)
    {
        $this->lastRunAt = $lastRunAt;

        return $this;
    }

    /**
     * @return int | null
     */
    public function getJobPid()
    {
        return $this->jobPid;
    }

    /**
     * @param int | null $jobPid
     *
     * @return Job
     */
    public function setJobPid($jobPid)
    {
        $this->jobPid = $jobPid;

        return $this;
    }

}