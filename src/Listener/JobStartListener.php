<?php
declare(strict_types=1);
namespace App\Listener;

use App\Entity\Job;
use App\Event\JobStartEvent;
use App\Repository\JobRepository;
use App\Util\MonitoringService\StatsManager;
use App\Util\ProcessHandlerService\ProcessHandler;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Process\Process;

class JobStartListener
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
    )
    {
        $this->entityManager = $entityManager;
        $this->jobRepository = $this->entityManager->getRepository(Job::class);
        $this->stats = $stats;
        $this->processHandler = $processHandler;
    }

    public function onJobStart(JobStartEvent $event)
    {
        /** @var Job $job */
        $job = $event->getJob();
        $process = new Process('php ../bin/console ' . $job->getCommand() . ' > /dev/null 2>&1 & echo $!');

        if ($this->processHandler->processStatus($job->getJobPid())) {
            $this->processHandler->killProcess($job->getJobPid());
        }

        $process->run();

        $job->setRunning(true);
        $job->setError(false);
        $job->setFinished(false);
        $job->setJobPid(intval($process->getOutput()));
        $this->entityManager->flush();
        $this->entityManager->clear();

        $this->stats->setStatName('job');
        $this->stats->event(['action' => 'start', 'name' => $job->getName()]);

        return;
    }

}