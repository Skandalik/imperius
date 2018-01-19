<?php
declare(strict_types=1);
namespace App\Listener;

use App\Entity\Sensor;
use App\Event\SensorAddEvent;
use App\Event\SensorFoundEvent;
use App\Factory\SensorFactory;
use App\Type\SensorStateEnumType;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class SensorFoundListener
{
    /** @var EventDispatcherInterface */
    private $eventDispatcher;

    /** @var SensorFactory */
    private $sensorFactory;

    public function __construct(EventDispatcherInterface $eventDispatcher, SensorFactory $sensorFactory)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->sensorFactory = $sensorFactory;
    }

    public function onSensorFound(SensorFoundEvent $event)
    {
        $event = new SensorAddEvent($this->createSensorEntity($event));

        $this->eventDispatcher->dispatch(SensorAddEvent::NAME, $event);

        return;
    }

    /**
     * @param SensorFoundEvent $event
     *
     * @return Sensor
     */
    private function createSensorEntity(SensorFoundEvent $event)
    {
        $sensor = $this->sensorFactory->create();

        $sensor->setUuid($event->getUuid());
        $sensor->setSensorIp($event->getIp());
        $sensor->setStatus($event->getStatus());
        $sensor->setFetchable($event->isFetchable());
        $sensor->setSwitchable($event->isSwitchable());
        $sensor->setAdjustable($event->isAdjustable());
        $sensor->setActive(true);
        $sensor->setStatus($event->getStatus());

        if ($event->isAdjustable()) {
            $sensor->setMinimumValue($event->getSensorValueRange()->getMinimumValue());
            $sensor->setMaximumValue($event->getSensorValueRange()->getMaximumValue());
        }

        return $sensor;
    }

}