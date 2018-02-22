<?php
declare(strict_types=1);
namespace App\Listener;

use App\Entity\Job;
use App\Event\JobStartEvent;
use App\Repository\JobRepository;
use App\Util\LogHelper\LogContextEnum;
use App\Util\MonitoringService\StatsManager;
use App\Util\ProcessHandlerService\ProcessHandler;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
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

    /** @var LoggerInterface */
    private $logger;

    /**
     * JobStartListener constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param StatsManager           $stats
     * @param ProcessHandler         $processHandler
     * @param LoggerInterface        $logger
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        StatsManager $stats,
        ProcessHandler $processHandler,
        LoggerInterface $logger
    ) {
        $this->entityManager = $entityManager;
        $this->jobRepository = $this->entityManager->getRepository(Job::class);
        $this->stats = $stats;
        $this->processHandler = $processHandler;
        $this->logger = $logger;
    }

    /**
     * @param JobStartEvent $event
     */
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

        $this->logger->info(
            sprintf(
                'Started job %s, command: %s, assigned PID: %s',
                $job->getName(),
                $job->getCommand(),
                $job->getJobPid()
            ),
            [
                LogContextEnum::JOB_ID      => $job->getId(),
                LogContextEnum::JOB_NAME    => $job->getName(),
                LogContextEnum::JOB_COMMAND => $job->getCommand(),
                LogContextEnum::JOB_PID     => $job->getJobPid(),
            ]
        );

        return;
    }

}