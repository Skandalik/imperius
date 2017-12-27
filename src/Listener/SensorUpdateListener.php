<?php
declare(strict_types=1);
namespace App\Listener;

use App\Entity\Sensor;
use App\Event\SensorUpdateEvent;
use Doctrine\ORM\EntityManagerInterface;

class SensorUpdateListener
{
    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function onSensorUpdate(SensorUpdateEvent $event)
    {
        //TODO zaprogramuj onSensorUpdate event
        $sensorRepo = $this->entityManager->getRepository(Sensor::class);
        /** @var Sensor $entity */
        $entity = $sensorRepo->findByUuid($event->getUuid());

        $entity->setStatus((int)$event->getData());
        $entity->setActive((bool) $event->getData());

        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        return;
    }

}