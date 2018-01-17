<?php
declare(strict_types=1);
namespace App\Listener;

use App\Entity\Job;
use App\Event\JobStartEvent;
use App\Repository\JobRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class JobStartListener
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

    public function onJobStart(JobStartEvent $event)
    {
        /** @var Job $job */
        $job = $event->getJob();
        $this->logger->error('Command ' . $job->getCommand() . ' with PID: ' . $job->getJobPid() . 'has started.');
        echo('Command ' . $job->getCommand() . ' with PID: ' . $job->getJobPid() . 'has started.');
        $job->setRunning(true);
        $job->setError(false);
        $job->setFinished(false);
        $job->setJobPid($event->getPid());
        $this->entityManager->flush();
        $this->entityManager->clear();

        return;
    }

}