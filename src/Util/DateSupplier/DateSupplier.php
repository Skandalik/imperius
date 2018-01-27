<?php
declare(strict_types=1);
namespace App\Util\DateSupplier;

use App\Entity\ScheduledBehavior;
use DateTime;
use DateTimeZone;
use function explode;

class DateSupplier
{
    /**
     * @param bool          $isRepeatable
     * @param string        $date
     * @param string        $time
     * @param DateTime | null $lastRunAt
     *
     * @return DateTime
     */
    public function convertRelativeDate(bool $isRepeatable, string $date, string $time, $lastRunAt = null)
    {
        if (!empty($time)) {
            $timeWords = explode(':', $time);
        }

        if ($isRepeatable) {
            if ('today' === $date) {
                if ($lastRunAt) {
                    $repeatableDate = new DateTime( 'tomorrow');
                } else {
                    $repeatableDate = new DateTime($date);
                }
            } elseif ('tomorrow' === $date) {
                $repeatableDate = new DateTime($date);
            } else {
                $repeatableDate = new DateTime('next ' . $date);
            }
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
        $nextRun = $this->convertRelativeDate(
            $scheduledBehavior->isRepeatable(),
            $scheduledBehavior->getRelativeDate(),
            $scheduledBehavior->getTime(),
            $scheduledBehavior->getLastRunAt()
        );
        $scheduledBehavior->setNextRunAt($nextRun);

        return $scheduledBehavior;
    }
}