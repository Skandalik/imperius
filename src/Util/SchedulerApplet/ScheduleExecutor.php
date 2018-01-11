<?php
declare(strict_types=1);
namespace App\Util\SchedulerApplet;

use App\Entity\ScheduledBehavior;
use App\Util\ActionExecutor\ActionExecutor;
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
        }
    }

    public function execute(ScheduledBehavior $scheduledBehavior)
    {
        if ($scheduledBehavior->getSensor()->getActive()) {
            $this->actionExecutor->executeAction(
                $scheduledBehavior->getSensor(),
                $scheduledBehavior->getScheduledAction(),
                strval($scheduledBehavior->getScheduledActionArgument())
            );
        }
    }
}