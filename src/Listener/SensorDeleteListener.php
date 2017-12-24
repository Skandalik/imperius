<?php
declare(strict_types=1);
namespace App\Listener;

use App\Event\SensorUpdateEvent;
use Doctrine\ORM\EntityManagerInterface;

class SensorDeleteListener
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function onSensorDelete(SensorUpdateEvent $event)
    {

        return;
    }

}