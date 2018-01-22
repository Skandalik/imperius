<?php
declare(strict_types=1);
namespace App\Listener;

use App\Entity\Job;
use App\Event\JobStartEvent;
use App\Event\JobStopEvent;
use App\Repository\JobRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class JobStopListener
{
    /** @var JobRepository */
    private $jobRepository;

    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var LoggerInterface */
    private $logger;

    public function __construct(EntityManagerInterface $entityManager, LoggerInterface $logger)
    {
        $this->entityManager = $entityManager;
        $this->jobRepository = $this->entityManager->getRepository(Job::class);
        $this->logger = $logger;
    }

    public function onJobStop(JobStopEvent $event)
    {
        /** @var Job $job */
        $job = $event->getJob();
        $job->setRunning(false);
        $job->setError(false);
        $job->setFinished(true);
        $job->setLastRunAt(new DateTime());
        $this->entityManager->flush();
        $this->entityManager->clear();

        return;
    }

}