<?php
declare(strict_types=1);
namespace App\Event;

use App\Entity\Job;
use App\Event\Enum\JobEventEnum;
use Symfony\Component\EventDispatcher\Event;

class JobStartEvent extends Event
{
    const NAME = JobEventEnum::JOB_START;

    /** @var Job $job */
    protected $job;

    /**
     * @param Job $job
     */
    public function __construct(Job $job)
    {
        $this->job = $job;
    }

    /**
     * @return Job
     */
    public function getJob(): Job
    {
        return $this->job;
    }
}