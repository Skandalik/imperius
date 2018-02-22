<?php
declare(strict_types=1);
namespace App\Event;

use App\Entity\Job;
use App\Event\Enum\JobEventEnum;
use Symfony\Component\EventDispatcher\Event;

class JobUpdateEvent extends Event
{
    const NAME = JobEventEnum::JOB_UPDATE;

    /** @var Job $job */
    protected $job;

    /** @var array */
    private $additionalData;

    /**
     * @param Job $job
     * @param     $additionalData
     */
    public function __construct(Job $job, $additionalData)
    {
        $this->job = $job;
        $this->additionalData = $additionalData;
    }

    /**
     * @return Job
     */
    public function getJob(): Job
    {
        return $this->job;
    }

    /**
     * @return mixed
     */
    public function getAdditionalData()
    {
        return $this->additionalData;
    }
}