<?php
declare(strict_types=1);
namespace App\Listener;

use App\Entity\Job;
use App\Event\JobInterruptEvent;
use App\Repository\JobRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class JobInterruptListener
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

    public function onJobInterrupt(JobInterruptEvent $event)
    {
        /** @var Job $job */
        $job = $this->jobRepository->findByCommandName($event->getCommandName());
        $job->setRunning(false);
        $job->setError(true);
        $job->setJobPid(null);
        $this->entityManager->flush();
        $this->entityManager->clear();

        return;
    }

}