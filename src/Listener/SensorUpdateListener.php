<?php
declare(strict_types=1);
namespace App\Listener;

use App\Entity\Sensor;
use App\Event\SensorFoundEvent;
use Doctrine\ORM\EntityManagerInterface;

class SensorUpdateListener
{
    public function onSensorUpdate(SensorFoundEvent $event, EntityManagerInterface $entityManager)
    {
        //TODO zaprogramuj onSensorUpdate event
    }

}