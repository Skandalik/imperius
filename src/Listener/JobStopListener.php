<?php
declare(strict_types=1);
namespace App\Listener;

use App\Command\RefreshSensorsDataCommand;
use App\Command\ScanScheduledTasksCommand;
use App\Command\ScanSensorsCommand;
use App\Entity\Job;
use App\Event\JobStopEvent;
use App\Repository\JobRepository;
use App\Util\MonitoringService\StatsManager;
use App\Util\ProcessHandlerService\ProcessHandler;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class JobStopListener
{
    /** @var JobRepository */
    private $jobRepository;

    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var StatsManager */
    private $stats;

    /** @var ProcessHandler */
    private $processHandler;

    /** @var EventDispatcherInterface */
    private $eventDispatcher;

    public function __construct(
        EntityManagerInterface $entityManager,
        StatsManager $stats,
        ProcessHandler $processHandler,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->entityManager = $entityManager;
        $this->jobRepository = $this->entityManager->getRepository(Job::class);
        $this->stats = $stats;
        $this->processHandler = $processHandler;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function onJobStop(JobStopEvent $event)
    {
        /** @var Job $job */
        $job = $event->getJob();

        if ($this->processHandler->processStatus($job->getJobPid())) {
            $this->processHandler->killProcess($job->getJobPid());
        }

        $job->setRunning(false);
        $job->setJobPid(null);
        $job->setError(false);
        $job->setFinished(true);
        $job->setLastRunAt(new DateTime());
        $this->entityManager->flush();
        $this->entityManager->clear();

        if (ScanSensorsCommand::SENSORS_SCAN === $job->getCommand()) {
            $events = [];
            $events[] = new JobStopEvent(
                $this->jobRepository->findByCommandName(ScanScheduledTasksCommand::SENSORS_SCHEDULED)
            );
            $events[] = new JobStopEvent(
                $this->jobRepository->findByCommandName(RefreshSensorsDataCommand::SENSORS_REFRESH)
            );
            foreach ($events as $event) {
                $this->eventDispatcher->dispatch(JobStopEvent::NAME, $event);
            }
        }

        $this->stats->setStatName('job');
        $this->stats->event(['action' => 'stop', 'name' => $job->getName()]);

        return;
    }

}