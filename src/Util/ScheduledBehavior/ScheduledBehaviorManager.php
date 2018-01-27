<?php
declare(strict_types=1);
namespace App\Util\ScheduledBehavior;

use App\Event\ScheduledTaskExecuteEvent;
use App\Util\ActionManager\ActionManager;
use App\Util\ScheduledBehavior\Checker\ScheduleChecker;
use App\Util\ScheduledBehavior\Refresher\ScheduleRefresher;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use const PHP_EOL;
use function date_format;

class ScheduledBehaviorManager
{
    /** @var ScheduleChecker */
    private $scheduleChecker;

    /** @var ScheduleRefresher */
    private $scheduleRenewer;

    /** @var ActionManager */
    private $actionManager;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var LoggerInterface
     */
    private $logger;

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
        $this->logger->error('Catched event!');
        $scheduledBehavior = $event->getBehavior();
        if (!$this->scheduleChecker->check($scheduledBehavior)) {
            echo PHP_EOL . "Date didn't match! Date now: " . date_format(
                    new DateTime(),
                    'Y-m-d H:i:s'
                ) . " scheduled date: " . date_format($scheduledBehavior->getNextRunAt(), 'Y-m-d H:i:s') . PHP_EOL;

            return false;
        }

        echo PHP_EOL . "Date matched!" . PHP_EOL;
        $this->actionManager->performAction($scheduledBehavior);
        $this->scheduleRenewer->refresh($scheduledBehavior);
        $this->entityManager->flush();
        $this->entityManager->clear();

        return true;
    }
}