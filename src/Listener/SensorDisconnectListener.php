<?php
declare(strict_types=1);
namespace App\Listener;

use App\Entity\Sensor;
use App\Event\SensorDisconnectEvent;
use App\Repository\SensorRepository;
use App\Util\MonitoringService\StatsManager;
use Doctrine\ORM\EntityManagerInterface;
use function boolval;

class SensorDisconnectListener
{
    /** @var SensorRepository */
    private $sensorRepository;

    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var StatsManager */
    private $stats;

    public function __construct(EntityManagerInterface $entityManager, StatsManager $stats)
    {
        $this->entityManager = $entityManager;
        $this->sensorRepository = $this->entityManager->getRepository(Sensor::class);
        $this->stats = $stats;
    }

    public function onSensorUpdate(SensorDisconnectEvent $event)
    {
        /** @var Sensor $sensor */
        $sensor = $this->sensorRepository->findByUuid($event->getUuid());

        $sensor->setActive(boolval($event->getState()));

        $this->entityManager->persist($sensor);
        $this->entityManager->flush();
        $this->entityManager->clear();

        $this->stats->setStatName('sensor');
        $this->stats->event(['action' => 'disconnect', 'uuid' => $sensor->getUuid(),]);

        return;
    }

}