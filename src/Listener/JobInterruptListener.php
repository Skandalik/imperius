<?php
declare(strict_types=1);
namespace App\Listener;

use App\Entity\Job;
use App\Event\JobInterruptEvent;
use App\Repository\JobRepository;
use App\Util\LogHelper\LogContextEnum;
use App\Util\MonitoringService\StatsManager;
use App\Util\ProcessHandlerService\ProcessHandler;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use function is_null;

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

    /** @var LoggerInterface */
    private $logger;

    /**
     * JobInterruptListener constructor.
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
     * @param JobInterruptEvent $event
     */
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

        $this->logger->error(
            sprintf(
                'Job: %s has been interrupted, command: %s, assigned PID: %s',
                $job->getName(),
                $job->getCommand(),
                $job->getJobPid() ?? 'none'
            ),
            [
                LogContextEnum::JOB_ID      => $job->getId(),
                LogContextEnum::JOB_NAME    => $job->getName(),
                LogContextEnum::JOB_COMMAND => $job->getCommand(),
                LogContextEnum::JOB_PID     => $job->getJobPid(),
            ]
        );

        if (!is_null($event->getException())) {
            $this->logger->error(
                sprintf(
                    'Job %s has been interrupted. Exception: %s',
                    $job->getCommand(),
                    $event->getException()->getTraceAsString()
                ),
                [
                    LogContextEnum::JOB_ID      => $job->getId(),
                    LogContextEnum::JOB_NAME    => $job->getName(),
                    LogContextEnum::JOB_COMMAND => $job->getCommand(),
                    LogContextEnum::JOB_PID     => $job->getJobPid(),
                ]
            );
        }

        return;
    }

}