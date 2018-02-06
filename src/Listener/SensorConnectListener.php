<?php
declare(strict_types=1);
namespace App\Listener;

use App\Entity\Sensor;
use App\Event\SensorConnectEvent;
use App\Event\SensorDisconnectEvent;
use App\Repository\SensorRepository;
use App\Util\LogHelper\LogContextEnum;
use App\Util\MonitoringService\StatsManager;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class SensorConnectListener
{
    /** @var SensorRepository */
    private $sensorRepository;

    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var StatsManager */
    private $stats;

    /** @var LoggerInterface */
    private $logger;

    /**
     * SensorDisconnectListener constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param StatsManager           $stats
     * @param LoggerInterface        $logger
     */
    public function __construct(EntityManagerInterface $entityManager, StatsManager $stats, LoggerInterface $logger)
    {
        $this->entityManager = $entityManager;
        $this->sensorRepository = $this->entityManager->getRepository(Sensor::class);
        $this->stats = $stats;
        $this->logger = $logger;
    }

    public function onSensorConnect(SensorConnectEvent $event)
    {
        /** @var Sensor $sensor */
        $sensor = $this->sensorRepository->findByUuid($event->getUuid());

        $sensor->setDisconnected(false);

        $this->entityManager->persist($sensor);
        $this->entityManager->flush();
        $this->entityManager->clear();

        $this->stats->setStatName('sensor');
        $this->stats->event(['action' => 'connect', 'uuid' => $sensor->getUuid()]);

        $this->logger->error(
            sprintf('Sensor ID: %s with UUID: %s has connected!', $sensor->getId(), $sensor->getUuid()),
            [
                LogContextEnum::SENSOR_ID   => $sensor->getId(),
                LogContextEnum::SENSOR_UUID => $sensor->getUuid(),
            ]
        );

        return;
    }

}