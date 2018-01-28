<?php
declare(strict_types=1);
namespace App\Listener;

use App\Event\SensorAddEvent;
use App\Util\MonitoringService\StatsManager;
use App\Util\SensorManager\SensorMosquittoPublisher;
use Doctrine\ORM\EntityManagerInterface;

class SensorAddListener
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var SensorMosquittoPublisher */
    private $publisher;

    /** @var StatsManager */
    private $stats;

    public function __construct(
        EntityManagerInterface $entityManager,
        SensorMosquittoPublisher $publisher,
        StatsManager $stats
    ) {
        $this->entityManager = $entityManager;
        $this->publisher = $publisher;
        $this->stats = $stats;
    }

    public function onSensorAdd(SensorAddEvent $event)
    {
        $this->entityManager->persist($event->getEntity());
        $this->entityManager->flush();
        $this->entityManager->clear();

        if ($event->isFromScan()) {
            $this->publisher->publishRegisteredSensorMessage($event->getEntity());
        }

        $this->stats->setStatName('sensor');
        $this->stats->event(['action' => 'disconnect', 'uuid' => $event->getEntity()->getUuid(),]);

        return;
    }

}