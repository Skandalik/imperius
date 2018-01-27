<?php
declare(strict_types=1);
namespace App\Listener;

use App\Entity\Job;
use App\Event\JobRunningEvent;
use App\Repository\JobRepository;
use Doctrine\ORM\EntityManagerInterface;

class JobRunningListener
{
    /** @var JobRepository */
    private $jobRepository;

    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->jobRepository = $this->entityManager->getRepository(Job::class);
    }

    public function onJobRunning(JobRunningEvent $event)
    {
        /** @var Job $job */
        $job = $event->getJob();
        $job->setRunning(true);
        $job->setError(false);
        $job->setFinished(false);
        $this->entityManager->flush();
        $this->entityManager->clear();

        return;
    }

}