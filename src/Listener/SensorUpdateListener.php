<?php
declare(strict_types=1);
namespace App\Listener;

use App\Entity\Sensor;
use App\Event\SensorUpdateEvent;
use App\Repository\SensorRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Redis;
use function date_format;

class SensorUpdateListener
{
    /** @var SensorRepository */
    private $sensorRepository;

    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var Redis */
    private $redis;

    public function __construct(Redis $redis, EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->sensorRepository = $this->entityManager->getRepository(Sensor::class);
        $this->redis = $redis;
    }

    /**
     * @param SensorUpdateEvent $event
     */
    public function onSensorUpdate(SensorUpdateEvent $event)
    {
        /** @var Sensor $sensor */
        $sensor = $this->sensorRepository->findByUuid($event->getUuid());
        $data = (int) $event->getData();
        $this->setStatusOrState($sensor, $data);
        $this->saveToRedis($sensor, $event);

        $this->entityManager->persist($sensor);
        $this->entityManager->flush();

        return;
    }

    /**
     * @param Sensor $sensor
     * @param        $data
     *
     * @return Sensor
     */
    private function setStatusOrState(Sensor $sensor, $data)
    {
        if (!$sensor->isFetchable()) {
            $sensor->setActive(!($data === 0));
        }
        return $sensor->setStatus($data);
    }

    /**
     * @param Sensor            $sensor
     * @param SensorUpdateEvent $event
     */
    private function saveToRedis(Sensor $sensor, SensorUpdateEvent $event)
    {
        $this->redis->hSet($sensor->getUuid(), date_format(new DateTime(), "Y-m-d_H:i:s"), $event->getData());
    }
}