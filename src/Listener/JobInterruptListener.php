<?php
declare(strict_types=1);
namespace App\Listener;

use App\Entity\Job;
use App\Event\JobInterruptEvent;
use App\Repository\JobRepository;
use App\Util\MonitoringService\StatsManager;
use App\Util\ProcessHandlerService\ProcessHandler;
use Doctrine\ORM\EntityManagerInterface;

class JobInterruptListener
{
    /** @var JobRepository */
    private $jobRepository;

    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var StatsManager */
    private $stats;

    /** @var ProcessHandler */
    private $processHandler;

    public function __construct(
        EntityManagerInterface $entityManager,
        StatsManager $stats,
        ProcessHandler $processHandler
    ) {
        $this->entityManager = $entityManager;
        $this->jobRepository = $this->entityManager->getRepository(Job::class);
        $this->stats = $stats;
        $this->processHandler = $processHandler;
    }

    public function onJobInterrupt(JobInterruptEvent $event)
    {
        /** @var Job $job */
        $job = $this->jobRepository->findByCommandName($event->getCommandName());

        if ($this->processHandler->processStatus($job->getJobPid())) {
            $this->processHandler->killProcess($job->getJobPid());
        }

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