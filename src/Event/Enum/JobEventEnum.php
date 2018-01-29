<?php
declare(strict_types=1);
namespace App\Event\Enum;

class JobEventEnum
{
    public const JOB_START = 'job.start';
    public const JOB_STOP = 'job.stop';
    public const JOB_CHECK = 'job.check';
    public const JOB_UPDATE = 'job.update';
    public const JOB_RUNNING = 'job.running';
    public const JOB_INTERRUPT = 'job.interrupt';

    public static $events = [
        self::JOB_START     => 'start',
        self::JOB_STOP      => 'stop',
        self::JOB_CHECK     => 'check',
        self::JOB_UPDATE    => 'update',
        self::JOB_RUNNING   => 'running',
        self::JOB_INTERRUPT => 'interrupt',
    ];

}