<?php
declare(strict_types=1);
namespace App\Listener;

use App\Entity\Job;
use App\Event\JobRunningEvent;
use App\Repository\JobRepository;
use App\Util\MonitoringService\StatsManager;
use Doctrine\ORM\EntityManagerInterface;

class JobRunningListener
{
    /** @var JobRepository */
    private $jobRepository;

    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var StatsManager */
    private $stats;

    public function __construct(EntityManagerInterface $entityManager, StatsManager $stats)
    {
        $this->entityManager = $entityManager;
        $this->jobRepository = $this->entityManager->getRepository(Job::class);
        $this->stats = $stats;
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

        $this->stats->setStatName('job');
        $this->stats->event(['action' => 'running', 'name' => $job->getName()]);

        return;
    }

}