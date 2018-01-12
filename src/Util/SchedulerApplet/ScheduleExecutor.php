<?php
declare(strict_types=1);
namespace App\Util\SchedulerApplet;

use App\Entity\ScheduledBehavior;
use App\Util\ActionExecutor\ActionExecutor;
use DateTime;
use const PHP_EOL;
use function strval;

class ScheduleExecutor
{
    /**
     * @var ScheduleChecker
     */
    private $scheduleChecker;

    /**
     * @var ScheduleRenewer
     */
    private $scheduleRenewer;

    /**
     * @var ActionExecutor
     */
    private $actionExecutor;

    public function __construct(
        ScheduleChecker $scheduleChecker,
        ScheduleRenewer $scheduleRenewer,
        ActionExecutor $actionExecutor
    ) {
        $this->scheduleChecker = $scheduleChecker;
        $this->scheduleRenewer = $scheduleRenewer;
        $this->actionExecutor = $actionExecutor;
    }

    public function executeScheduledBehavior(ScheduledBehavior $scheduledBehavior)
    {
        if ($this->scheduleChecker->checkDate($scheduledBehavior)) {
            $this->execute($scheduledBehavior);

            return;
        }
        echo "Date doesn't match: " . $scheduledBehavior->getSensor()->getId() . PHP_EOL;

        return;
    }

    private function execute(ScheduledBehavior $scheduledBehavior)
    {
        echo PHP_EOL;
        echo "Executing behavior for: " . $scheduledBehavior->getSensor()->getId() . PHP_EOL;
        echo PHP_EOL;

        $this->actionExecutor->executeAction(
            $scheduledBehavior->getSensor(),
            $scheduledBehavior->getScheduledAction(),
            strval($scheduledBehavior->getScheduledActionArgument())
        );
        $this->scheduleRenewer->refreshSchedule($scheduledBehavior);
    }
}