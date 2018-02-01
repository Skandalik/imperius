<?php
declare(strict_types=1);
namespace App\Util\ScheduledBehavior\Refresher;

use App\Entity\ScheduledBehavior;
use App\Util\DateSupplier\DateSupplier;
use App\Util\LogHelper\LogContextEnum;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class ScheduleRefresher
{
    /** @var DateSupplier */
    private $dateSupplier;

    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var LoggerInterface */
    private $logger;

    /**
     * @param DateSupplier           $dateSupplier
     * @param EntityManagerInterface $entityManager
     * @param LoggerInterface        $logger
     */
    public function __construct(
        DateSupplier $dateSupplier,
        EntityManagerInterface $entityManager,
        LoggerInterface $logger
    ) {
        $this->dateSupplier = $dateSupplier;
        $this->entityManager = $entityManager;
        $this->logger = $logger;
    }

    /**
     * @param ScheduledBehavior $scheduledBehavior
     */
    public function refresh(ScheduledBehavior $scheduledBehavior)
    {
        $nowRunned = $scheduledBehavior->getNextRunAt();

        $scheduledBehavior->setLastRunAt($this->dateSupplier->getNowDate());
        $this->dateSupplier->setNextRunDate($scheduledBehavior);

        $this->logger->info(
            sprintf(
                'Refreshing scheduled behavior %s from %s to %s',
                $scheduledBehavior->getId(),
                date_format($nowRunned, 'Y-m-d H:i'),
                date_format($scheduledBehavior->getNextRunAt(), 'Y-m-d H:i')
            ),
            [
                LogContextEnum::SCHEDULED_BEHAVIOR_ID => $scheduledBehavior->getId(),
                LogContextEnum::SENSOR_ID             => $scheduledBehavior->getSensor()->getId(),
                LogContextEnum::SENSOR_UUID           => $scheduledBehavior->getSensor()->getUuid(),
                LogContextEnum::ACTION_SENSOR_ID      => $scheduledBehavior->getActionSensor()->getId(),
                LogContextEnum::ACTION_SENSOR_UUID    => $scheduledBehavior->getActionSensor()->getUuid(),
            ]
        );
    }

}