<?php
declare(strict_types=1);
namespace App\Listener;

use App\Event\SensorAddEvent;
use App\Util\LogHelper\LogContextEnum;
use App\Util\MonitoringService\StatsManager;
use App\Util\SensorManager\SensorMosquittoPublisher;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class SensorAddListener
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var SensorMosquittoPublisher */
    private $publisher;

    /** @var StatsManager */
    private $stats;

    /** @var LoggerInterface */
    private $logger;

    /**
     * SensorAddListener constructor.
     *
     * @param EntityManagerInterface   $entityManager
     * @param SensorMosquittoPublisher $publisher
     * @param StatsManager             $stats
     * @param LoggerInterface          $logger
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        SensorMosquittoPublisher $publisher,
        StatsManager $stats,
        LoggerInterface $logger
    ) {
        $this->entityManager = $entityManager;
        $this->publisher = $publisher;
        $this->stats = $stats;
        $this->logger = $logger;
    }

    /**
     * @param SensorAddEvent $event
     */
    public function onSensorAdd(SensorAddEvent $event)
    {
        $sensor = $event->getEntity();

        $this->entityManager->persist($sensor);
        $this->entityManager->flush();
        $this->entityManager->clear();

        if ($event->isFromScan()) {
            $this->publisher->publishRegisteredSensorMessage($sensor);
        }

        $this->stats->setStatName('sensor');
        $this->stats->event(['action' => 'disconnect', 'uuid' => $sensor->getUuid(),]);

        $this->logger->info(
            sprintf('Added new Sensor: %s with UUID: %s', $sensor->getId(), $sensor->getUuid()),
            [
                LogContextEnum::SENSOR_UUID => $sensor->getUuid(),
            ]
        );

        return;
    }

}