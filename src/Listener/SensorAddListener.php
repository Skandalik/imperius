<?php
declare(strict_types=1);
namespace App\Listener;

use App\Entity\Sensor;
use App\Event\SensorAddEvent;
use App\Event\SensorFoundEvent;
use App\Util\MosquittoWrapper\MosquittoPublisher;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class SensorAddListener
{
    public function onSensorAdd(
        SensorAddEvent $event,
        EntityManagerInterface $entityManager,
        MosquittoPublisher $mosquittoPublisher
    ) {
        $entityManager->persist($event->getEntity());
        $entityManager->flush();

        if ($event->isFromScan()) {
            $mosquittoPublisher->publish('registered', '', 2, false);
        }
    }

}