<?php
declare(strict_types=1);
namespace App\Listener;

use App\Entity\Job;
use App\Event\JobInterruptEvent;
use App\Repository\JobRepository;
use App\Util\MonitoringService\StatsManager;
use Doctrine\ORM\EntityManagerInterface;

class JobInterruptListener
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

    public function onJobInterrupt(JobInterruptEvent $event)
    {
        /** @var Job $job */
        $job = $this->jobRepository->findByCommandName($event->getCommandName());
        $job->setRunning(false);
        $job->setError(true);
        $job->setJobPid(null);
        $this->entityManager->flush();
        $this->entityManager->clear();

        $this->stats->setStatName('job');
        $this->stats->event(['action' => 'interrupt', 'name' => $job->getName()]);

        return;
    }

}