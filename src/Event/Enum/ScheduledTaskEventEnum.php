<?php
declare(strict_types=1);
namespace App\Event\Enum;

class ScheduledTaskEventEnum
{
    public const SCHEDULED_TASK_EXECUTE = 'scheduled_task.execute';

    public static $events = [
        self::SCHEDULED_TASK_EXECUTE=> 'execute',
    ];

}