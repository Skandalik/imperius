<?php
declare(strict_types=1);
namespace App\Listener;

use App\Entity\Job;
use App\Event\JobStopEvent;
use App\Repository\JobRepository;
use App\Util\MonitoringService\StatsManager;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

class JobStopListener
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

        $this->stats->setStatName('job');
        $this->stats->event(['action' => 'stop', 'name' => $job->getName()]);

        return;
    }

}