<?php
declare(strict_types=1);
namespace App\Util\ScheduledBehavior;

use App\Event\ScheduledTaskExecuteEvent;
use App\Util\ActionManager\ActionManager;
use App\Util\LogHelper\LogContextEnum;
use App\Util\ScheduledBehavior\Checker\ScheduleChecker;
use App\Util\ScheduledBehavior\Refresher\ScheduleRefresher;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class ScheduledBehaviorManager
{
    /** @var ScheduleChecker */
    private $scheduleChecker;

    /** @var ScheduleRefresher */
    private $scheduleRenewer;

    /** @var ActionManager */
    private $actionManager;

    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var LoggerInterface */
    private $logger;

    /**
     * ScheduledBehaviorManager constructor.
     *
     * @param ScheduleChecker        $scheduleChecker
     * @param ScheduleRefresher      $scheduleRenewer
     * @param ActionManager          $actionManager
     * @param EntityManagerInterface $entityManager
     * @param LoggerInterface        $logger
     */
    public function __construct(
        ScheduleChecker $scheduleChecker,
        ScheduleRefresher $scheduleRenewer,
        ActionManager $actionManager,
        EntityManagerInterface $entityManager,
        LoggerInterface $logger
    ) {
        $this->scheduleChecker = $scheduleChecker;
        $this->scheduleRenewer = $scheduleRenewer;
        $this->actionManager = $actionManager;
        $this->entityManager = $entityManager;
        $this->logger = $logger;
    }

    /**
     * @param ScheduledTaskExecuteEvent $event
     *
     * @return bool
     */
    public function execute(ScheduledTaskExecuteEvent $event)
    {
        $scheduledBehavior = $event->getBehavior();
        if (!$this->scheduleChecker->check($scheduledBehavior)) {
            return false;
        }
        $this->logger->info(
            sprintf(
                'Scheduled behavior %s for sensor %s matched!.',
                $scheduledBehavior->getId(),
                $scheduledBehavior->getSensor()->getUuid()
            ),
            [
                LogContextEnum::SCHEDULED_BEHAVIOR_ID => $scheduledBehavior->getId(),
                LogContextEnum::SENSOR_ID             => $scheduledBehavior->getSensor()->getId(),
                LogContextEnum::SENSOR_UUID           => $scheduledBehavior->getSensor()->getUuid(),
            ]
        );

        $this->actionManager->performAction($scheduledBehavior);
        $this->scheduleRenewer->refresh($scheduledBehavior);
        $this->entityManager->flush();
        $this->entityManager->clear();

        return true;
    }
}