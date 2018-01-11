<?php
declare(strict_types=1);
namespace App\Util\DateSupplier;

use App\Entity\ScheduledBehavior;
use DateTime;
use function explode;
use function in_array;

class DateSupplier
{
    public function convertRelativeDate(string $date, string $time)
    {
        $dateWords = explode(' ', $date);
        $timeWords = explode(':', $time);

        if (in_array('every', $dateWords)) {
            $regularDate = new DateTime('next ' . $dateWords[1]);
            $regularDate->setTime((int)$timeWords[0], (int)$timeWords[1]);

            return $regularDate;
        }

        $regularDate = new DateTime($date);
        $regularDate->setTime((int)$timeWords[0], (int)$timeWords[1]);

        return $regularDate;
    }

    public function getNowDate()
    {
        return new DateTime();
    }

    public function setNextRunDate(ScheduledBehavior $scheduledBehavior)
    {
        $nextRun = $this->convertRelativeDate($scheduledBehavior->getRelativeDate(), $scheduledBehavior->getTime());

        $scheduledBehavior->setNextRunAt($nextRun);
    }
}