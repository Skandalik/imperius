<?php
declare(strict_types=1);
namespace App\Listener;

use App\Entity\Job;
use App\Event\Enum\JobEventEnum;
use App\Event\JobCheckEvent;
use App\Event\JobInterruptEvent;
use App\Event\JobRunningEvent;
use App\Event\JobStopEvent;
use App\Repository\JobRepository;
use App\Util\MonitoringService\StatsManager;
use App\Util\ProcessHandlerService\ProcessHandler;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class JobCheckListener
{
    /** @var JobRepository */
    private $jobRepository;

    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var StatsManager */
    private $stats;

    /** @var ProcessHandler */
    private $processHandler;

    /** @var EventDispatcherInterface */
    private $eventDispatcher;

    public function __construct(
        EntityManagerInterface $entityManager,
        StatsManager $stats,
        ProcessHandler $processHandler,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->entityManager = $entityManager;
        $this->jobRepository = $this->entityManager->getRepository(Job::class);
        $this->stats = $stats;
        $this->processHandler = $processHandler;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function onJobCheck(JobCheckEvent $event)
    {
        /** @var Job $job */
        $job = $event->getJob();
        if ($job->isRunning()) {
            if ($this->processHandler->processStatus($job->getJobPid())) {
                $name = JobEventEnum::JOB_RUNNING;
                $event = new JobRunningEvent($job, $job->getJobPid());
            } else {
                if ($job->isError()) {
                    $name = JobEventEnum::JOB_INTERRUPT;
                    $event = new JobInterruptEvent($job->getCommand());
                } elseif (!$job->isError()) {
                    $name = JobEventEnum::JOB_STOP;
                    $event = new JobStopEvent($job);
                }
            }

            $this->eventDispatcher->dispatch($name, $event);
        }

        return;
    }

}