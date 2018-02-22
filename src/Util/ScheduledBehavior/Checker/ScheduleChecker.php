<?php
declare(strict_types=1);
namespace App\Util\ScheduledBehavior\Checker;

use App\Entity\ScheduledBehavior;
use App\Util\DateSupplier\DateSupplier;

class ScheduleChecker
{
    /** @var DateSupplier */
    private $dateSupplier;

    /**
     *
     * @param DateSupplier $dateSupplier
     */
    public function __construct(DateSupplier $dateSupplier)
    {
        $this->dateSupplier = $dateSupplier;
    }

    /**
     * @param ScheduledBehavior $scheduledBehavior
     *
     * @return bool
     */
    public function check(ScheduledBehavior $scheduledBehavior): bool
    {
        if ($scheduledBehavior->isFinished()){
            return false;
        }

        $now = date_format($this->dateSupplier->getNowDate(), 'Y-m-d H:i');

        if (date_format($scheduledBehavior->getNextRunAt(), 'Y-m-d H:i') === $now) {
            return true;
        }

        return false;
    }

}