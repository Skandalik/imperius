<?php
declare(strict_types=1);
namespace App\Event;

use App\Entity\Job;
use App\Event\Enum\JobEventEnum;
use Symfony\Component\EventDispatcher\Event;

class JobStopEvent extends Event
{
    const NAME = JobEventEnum::JOB_STOP;

    /** @var Job $job */
    protected $job;

    /** @var int | null $pid */
    protected $pid;

    /**
     * @param Job $job
     * @param        $pid
     */
    public function __construct(Job $job, $pid)
    {
        $this->job = $job;
        $this->pid = $pid;
    }

    /**
     * @return Job
     */
    public function getJob(): Job
    {
        return $this->job;
    }

    /**
     * @return int|null
     */
    public function getPid()
    {
        return $this->pid;
    }
}