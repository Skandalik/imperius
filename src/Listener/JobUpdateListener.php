<?php
declare(strict_types=1);
namespace App\Listener;

use App\Entity\Job;
use App\Event\Enum\JobEventEnum;
use App\Event\JobStartEvent;
use App\Event\JobUpdateEvent;
use App\Repository\JobRepository;
use App\Util\LogHelper\LogContextEnum;
use App\Util\MonitoringService\StatsManager;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class JobUpdateListener
{
    /** @var JobRepository */
    private $jobRepository;

    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var StatsManager */
    private $stats;

    /** @var EventDispatcherInterface */
    private $eventDispatcher;

    /** @var LoggerInterface */
    private $logger;

    /**
     * JobUpdateListener constructor.
     *
     * @param EntityManagerInterface   $entityManager
     * @param StatsManager             $stats
     * @param EventDispatcherInterface $eventDispatcher
     * @param LoggerInterface          $logger
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        StatsManager $stats,
        EventDispatcherInterface $eventDispatcher,
        LoggerInterface $logger
    ) {
        $this->entityManager = $entityManager;
        $this->jobRepository = $this->entityManager->getRepository(Job::class);
        $this->stats = $stats;
        $this->eventDispatcher = $eventDispatcher;
        $this->logger = $logger;
    }

    /**
     * @param JobUpdateEvent $event
     */
    public function onJobUpdate(JobUpdateEvent $event)
    {
        /** @var Job $job */
        $job = $event->getJob();

        $data = $event->getAdditionalData();
        $job->setAdditionalData($data);

        $this->entityManager->flush();
        $this->entityManager->clear();

        $startEvent = new JobStartEvent($job);
        $this->eventDispatcher->dispatch(JobEventEnum::JOB_START, $startEvent);

        $this->logger->info(
            sprintf(
                'Started Job: %s, command: %s, assigned PID: %s, additional data: ',
                $job->getName(),
                $job->getCommand(),
                $job->getJobPid(),
                $job->getAdditionalData()
            ),
            [
                LogContextEnum::JOB_ID      => $job->getId(),
                LogContextEnum::JOB_NAME    => $job->getName(),
                LogContextEnum::JOB_COMMAND => $job->getCommand(),
                LogContextEnum::JOB_PID     => $job->getJobPid(),
            ]
        );

        return;
    }

}