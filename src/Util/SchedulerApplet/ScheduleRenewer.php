<?php
declare(strict_types=1);
namespace App\Util\SchedulerApplet;

use App\Entity\ScheduledBehavior;
use App\Util\DateSupplier\DateSupplier;
use Doctrine\ORM\EntityManagerInterface;

class ScheduleRenewer
{
    /**
     * @var DateSupplier
     */
    private $dateSupplier;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(DateSupplier $dateSupplier, EntityManagerInterface $entityManager)
    {
        $this->dateSupplier = $dateSupplier;
        $this->entityManager = $entityManager;
    }

    public function refreshSchedule(ScheduledBehavior $scheduledBehavior)
    {
        $scheduledBehavior->setLastRunAt($this->dateSupplier->getNowDate());
        $this->dateSupplier->setNextRunDate($scheduledBehavior);

        $this->entityManager->flush();
        $this->entityManager->clear();
    }

}