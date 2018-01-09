<?php
declare(strict_types=1);
namespace App\Listener;

use App\Entity\Sensor;
use App\Event\SensorDisconnectEvent;
use App\Repository\SensorRepository;
use Doctrine\ORM\EntityManagerInterface;

class SensorDisconnectListener
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

    public function onSensorUpdate(SensorDisconnectEvent $event)
    {
        /** @var Sensor $sensor */
        $sensor = $this->sensorRepository->findByUuid($event->getUuid());

        $sensor->setState($event->getState());

        $this->entityManager->persist($sensor);
        $this->entityManager->flush();

        return;
    }

}