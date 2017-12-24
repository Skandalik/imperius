<?php
declare(strict_types=1);
namespace App\Listener;

use App\Entity\Sensor;
use App\Event\SensorAddEvent;
use App\Event\SensorFoundEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class SensorFoundListener
{
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    public function onSensorFound(SensorFoundEvent $event)
    {
        $entity = new Sensor();

        $entity->setUuid($event->getUuid());
        $entity->setSensorIp($event->getIp());
        $entity->setValue($event->getStatus());
        $this->setActiveAndStatus($event, $entity);
        $entity->setSwitchable($event->isSwitchable());

        $event = new SensorAddEvent($entity);

        $this->eventDispatcher->dispatch(SensorAddEvent::NAME, $event);

        return;
    }

    /**
     * @param SensorFoundEvent $event
     * @param Sensor           $entity
     *
     * @return Sensor
     */
    private function setActiveAndStatus(SensorFoundEvent $event, $entity): Sensor
    {
        if (0 !== $event->getStatus()) {
            $entity->setActive(true);
            $entity->setValue($event->getStatus());

            return $entity;
        }

        $entity->setActive(false);
        $entity->setValue($event->getStatus());

        return $entity;
    }

}