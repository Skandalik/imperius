<?php
declare(strict_types=1);
namespace App\Listener;

use App\Event\SensorAddEvent;
use App\Util\MosquittoWrapper\MosquittoPublisher;
use App\Util\SensorManager\SensorMosquittoPublisher;
use App\Util\TopicGenerator\TopicGenerator;
use Doctrine\ORM\EntityManagerInterface;

class SensorAddListener
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var SensorMosquittoPublisher */
    private $publisher;

    public function __construct(EntityManagerInterface $entityManager, SensorMosquittoPublisher $publisher)
    {
        $this->entityManager = $entityManager;
        $this->publisher = $publisher;
    }

    public function onSensorAdd(SensorAddEvent $event)
    {
        echo "Sensor " . $event->getEntity()->getUuid() . ' has been registered sucessfully.';
        $this->entityManager->persist($event->getEntity());
        $this->entityManager->flush();
        $this->entityManager->clear();

        if ($event->isFromScan()) {
            $this->publisher->publishRegisteredSensorMessage($event->getEntity());
        }

        return;
    }

}