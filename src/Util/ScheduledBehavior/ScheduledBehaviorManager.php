<?php
declare(strict_types=1);
namespace App\Util\ScheduledBehavior;

use App\Entity\ScheduledBehavior;
use App\Util\ActionManager\ActionManager;
use App\Util\ScheduledBehavior\Checker\ScheduleChecker;
use App\Util\ScheduledBehavior\Refresher\ScheduleRefresher;

class ScheduledBehaviorManager
{
    /** @var ScheduleChecker */
    private $scheduleChecker;

    /** @var ScheduleRefresher */
    private $scheduleRenewer;

    /** @var ActionManager */
    private $actionManager;

    public function __construct(
        ScheduleChecker $scheduleChecker,
        ScheduleRefresher $scheduleRenewer,
        ActionManager $actionManager
    ) {
        $this->scheduleChecker = $scheduleChecker;
        $this->scheduleRenewer = $scheduleRenewer;
        $this->actionManager = $actionManager;
    }

    public function execute(ScheduledBehavior $scheduledBehavior)
    {
        if (!$this->scheduleChecker->check($scheduledBehavior)) {
            return false;
        }

        $this->actionManager->performAction($scheduledBehavior);
        $this->scheduleRenewer->refresh($scheduledBehavior);
        return true;
    }
}