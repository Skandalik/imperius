<?php
declare(strict_types=1);
namespace App\Listener;

use App\Entity\Sensor;
use App\Event\SensorAddEvent;
use App\Event\SensorFoundEvent;
use App\Util\MosquittoWrapper\MosquittoPublisher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class SensorFoundListener
{
    public function onSensorFound(
        SensorFoundEvent $event
    ) {
        echo $event->getUuid();

        $entity = new Sensor();

        $entity->setUuid($event->getUuid());
        $entity->setSensorIp($event->getIp());
        $entity->setValue($event->getValue());
        $entity->setSwitchable($event->isSwitchable());
    }

}