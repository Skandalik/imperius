<?php
declare(strict_types=1);
namespace App\Listener;

use App\Entity\Sensor;
use App\Event\SensorUpdateEvent;
use App\Repository\SensorRepository;
use App\Type\SensorStateEnumType;
use function boolval;
use Doctrine\ORM\EntityManagerInterface;
use function in_array;

class SensorUpdateListener
{
    /** @var SensorRepository */
    private $sensorRepository;

    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->sensorRepository = $this->entityManager->getRepository(Sensor::class);
    }

    public function onSensorUpdate(SensorUpdateEvent $event)
    {
        /** @var Sensor $sensor */
        $sensor = $this->sensorRepository->findByUuid($event->getUuid());

        $this->setStatusOrState($sensor, $event);

        $this->entityManager->persist($sensor);
        $this->entityManager->flush();

        return;
    }

    private function setStatusOrState(Sensor $sensor, SensorUpdateEvent $event)
    {
        if ($event->getData() === SensorStateEnumType::SENSOR_ON || $event->getData() === SensorStateEnumType::SENSOR_OFF) {
            return $sensor->setActive(boolval(SensorStateEnumType::getFlippedValue($event->getData())));
        }
        return $sensor->setStatus((int)$event->getData());
    }

}