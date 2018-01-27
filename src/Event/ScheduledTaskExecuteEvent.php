<?php
declare(strict_types=1);
namespace App\Event;

use App\Entity\ScheduledBehavior;
use App\Event\Enum\ScheduledTaskEventEnum;
use Symfony\Component\EventDispatcher\Event;

class ScheduledTaskExecuteEvent extends Event
{
    const NAME = ScheduledTaskEventEnum::SCHEDULED_TASK_EXECUTE;

    /** @var ScheduledBehavior  */
    protected $behavior;

    /**
     * @param ScheduledBehavior $behavior
     */
    public function __construct(ScheduledBehavior $behavior)
    {
        $this->behavior = $behavior;
    }

    /**
     * @return ScheduledBehavior
     */
    public function getBehavior(): ScheduledBehavior
    {
        return $this->behavior;
    }
}