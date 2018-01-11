<?php
declare(strict_types=1);
namespace App\Util\SchedulerApplet;

use App\Entity\ScheduledBehavior;
use App\Util\DateSupplier\DateSupplier;

class ScheduleChecker
{
    /**
     * @var DateSupplier
     */
    private $dateSupplier;

    public function __construct(DateSupplier $dateSupplier)
    {
        $this->dateSupplier = $dateSupplier;
    }

    public function checkDate(ScheduledBehavior $scheduledBehavior)
    {
        $now = date_format($this->dateSupplier->getNowDate(), 'Y-m-d H:i');

        if ($scheduledBehavior->getNextRunAt() === $now) {
            return true;
        }

        return false;
    }

}