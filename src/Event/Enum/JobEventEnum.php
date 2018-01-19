<?php
declare(strict_types=1);
namespace App\Event\Enum;

class JobEventEnum
{
    public const JOB_START = 'job.start';
    public const JOB_INTERRUPT = 'job.interrupt';

    public static $events = [
        self::JOB_START     => 'start',
        self::JOB_INTERRUPT => 'interrupt',
    ];

}