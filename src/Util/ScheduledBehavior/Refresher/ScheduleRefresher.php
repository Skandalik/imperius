<?php
declare(strict_types=1);
namespace App\Util\ScheduledBehavior\Refresher;

use App\Entity\ScheduledBehavior;
use App\Util\DateSupplier\DateSupplier;
use Doctrine\ORM\EntityManagerInterface;

class ScheduleRefresher
{
    /** @var DateSupplier */
    private $dateSupplier;

    /** @var EntityManagerInterface */
    private $entityManager;

    /**
     * @param DateSupplier           $dateSupplier
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(DateSupplier $dateSupplier, EntityManagerInterface $entityManager)
    {
        $this->dateSupplier = $dateSupplier;
        $this->entityManager = $entityManager;
    }

    /**
     * @param ScheduledBehavior $scheduledBehavior
     */
    public function refresh(ScheduledBehavior $scheduledBehavior)
    {
        $scheduledBehavior->setLastRunAt($this->dateSupplier->getNowDate());
        $this->dateSupplier->setNextRunDate($scheduledBehavior);
    }

}