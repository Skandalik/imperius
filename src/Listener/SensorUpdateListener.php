<?php
declare(strict_types=1);
namespace App\Listener;

use App\Entity\Sensor;
use App\Event\SensorUpdateEvent;
use App\Repository\SensorRepository;
use App\Util\LogHelper\LogContextEnum;
use App\Util\MonitoringService\StatsManager;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Redis;
use function date_format;
use function sprintf;

class SensorUpdateListener
{
    /** @var string */
    private $type = "";

    /** @var SensorRepository */
    private $sensorRepository;

    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var Redis */
    private $redis;

    /** @var StatsManager */
    private $stats;

    /** @var LoggerInterface */
    private $logger;

    /**
     * SensorUpdateListener constructor.
     *
     * @param Redis                  $redis
     * @param EntityManagerInterface $entityManager
     * @param StatsManager           $stats
     * @param LoggerInterface        $logger
     */
    public function __construct(
        Redis $redis,
        EntityManagerInterface $entityManager,
        StatsManager $stats,
        LoggerInterface $logger
    ) {
        $this->entityManager = $entityManager;
        $this->sensorRepository = $this->entityManager->getRepository(Sensor::class);
        $this->redis = $redis;
        $this->stats = $stats;
        $this->logger = $logger;
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
        $sensor->setLastDataSentAt(new DateTime());
        $this->saveToRedis($sensor, $event);

        $this->entityManager->persist($sensor);
        $this->entityManager->flush();

        $this->setStatType($sensor);
        $this->stats->setStatName('sensor');
        $this->stats->event(['action' => 'update']);
        $this->stats->gauge(
            [
                'type'      => $this->type,
                'data_type' => $sensor->getDataType(),
                'uuid'      => $sensor->getUuid(),
            ],
            $data
        );

        $this->logger->info(
            sprintf('Updated Sensor: %s with UUID: %s', $sensor->getId(), $sensor->getUuid()),
            [
                LogContextEnum::SENSOR_UUID => $sensor->getUuid(),
            ]
        );

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

    /**
     * @param Sensor $sensor
     */
    private function setStatType(Sensor $sensor)
    {
        if ($sensor->isFetchable()) {
            $this->type = 'fetch';
        } elseif ($sensor->isAdjustable()) {
            $this->type = 'adjust';
        } elseif ($sensor->isSwitchable()) {
            $this->type = 'switch';
        }
    }
}