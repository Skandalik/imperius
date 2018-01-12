<?php
declare(strict_types=1);
namespace App\Util\DateSupplier;

use App\Entity\ScheduledBehavior;
use App\Util\DateSupplier\Enum\DateSupplierTimeEnum;
use function array_intersect;
use DateInterval;
use DateTime;
use DateTimeZone;
use function explode;
use function in_array;

class DateSupplier
{
    /**
     * @param string $date
     * @param string $time
     *
     * @return DateTime
     */
    public function convertRelativeDate(string $date, string $time)
    {
        $dateWords = explode(' ', $date);
        if (!empty($time)) {
            $timeWords = explode(':', $time);
        }

        if (in_array('every', $dateWords)) {
            if (count(array_intersect($dateWords, DateSupplierTimeEnum::getReadableValues()))) {
                $repeatableDate = new DateTime('now', new DateTimeZone('Europe/Warsaw'));
                $repeatableDate->add(new DateInterval(sprintf('PT%sM', $dateWords[1])));

                return $repeatableDate;
            }
            $repeatableDate = new DateTime('next ' . $dateWords[1]);
            $repeatableDate->setTime((int) $timeWords[0], (int) $timeWords[1]);

            return $repeatableDate;
        }


        $regularDate = new DateTime($date);
        $regularDate->setTime((int) $timeWords[0], (int) $timeWords[1]);

        return $regularDate;
    }

    /**
     * @return DateTime
     */
    public function getNowDate()
    {
        return new DateTime('now', new DateTimeZone('Europe/Warsaw'));
    }

    /**
     * @param ScheduledBehavior $scheduledBehavior
     *
     * @return ScheduledBehavior
     */
    public function setNextRunDate(ScheduledBehavior $scheduledBehavior)
    {
        if (!$scheduledBehavior->isRepeatable() && !$scheduledBehavior->isFinished()) {
            $scheduledBehavior->setNextRunAt(null);
            $scheduledBehavior->setFinished(true);

            return $scheduledBehavior;
        }
        $nextRun = $this->convertRelativeDate($scheduledBehavior->getRelativeDate(), $scheduledBehavior->getTime());
        $scheduledBehavior->setNextRunAt($nextRun);

        return $scheduledBehavior;
    }
}