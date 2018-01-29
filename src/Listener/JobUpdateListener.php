<?php
declare(strict_types=1);
namespace App\Listener;

use App\Entity\Job;
use App\Event\Enum\JobEventEnum;
use App\Event\JobStartEvent;
use App\Event\JobUpdateEvent;
use App\Repository\JobRepository;
use App\Util\MonitoringService\StatsManager;
use Doctrine\ORM\EntityManagerInterface;
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

    public function __construct(
        EntityManagerInterface $entityManager,
        StatsManager $stats,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->entityManager = $entityManager;
        $this->jobRepository = $this->entityManager->getRepository(Job::class);
        $this->stats = $stats;
        $this->eventDispatcher = $eventDispatcher;
    }

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

        return;
    }

}