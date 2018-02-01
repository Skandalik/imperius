<?php
declare(strict_types=1);
namespace App\Listener;

use App\Entity\Sensor;
use App\Event\SensorAddEvent;
use App\Event\SensorFoundEvent;
use App\Factory\SensorFactory;
use App\Util\LogHelper\LogContextEnum;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class SensorFoundListener
{
    /** @var EventDispatcherInterface */
    private $eventDispatcher;

    /** @var SensorFactory */
    private $sensorFactory;

    /** @var LoggerInterface */
    private $logger;

    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        SensorFactory $sensorFactory,
        LoggerInterface $logger
    ) {
        $this->eventDispatcher = $eventDispatcher;
        $this->sensorFactory = $sensorFactory;
        $this->logger = $logger;
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
        $sensor->setDataType($event->getDataType());

        if ($event->isAdjustable()) {
            $sensor->setMinimumValue($event->getSensorValueRange()->getMinimumValue());
            $sensor->setMaximumValue($event->getSensorValueRange()->getMaximumValue());
        }

        $this->logger->info(
            sprintf('Found new Sensor with UUID: %s!', $sensor->getUuid()),
            [
                LogContextEnum::SENSOR_UUID => $sensor->getUuid(),
            ]
        );

        return $sensor;
    }

}