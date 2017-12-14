<?php
declare(strict_types=1);
namespace App\Listener;

use App\Entity\Sensor;
use App\Event\SensorAddEvent;
use App\Event\SensorFoundEvent;
use App\Event\SensorGetDataEvent;
use App\Event\SensorGetStatusEvent;
use App\Event\SensorSetDataEvent;
use App\Event\SensorSetStatusEvent;
use App\Event\SensorStatusEvent;
use App\Util\MosquittoWrapper\MosquittoPublisher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class SensorStatusListener
{
    public function onSensorStatus(
        SensorStatusEvent $event,
        EventDispatcherInterface $eventDispatcher
    ) {
        $statusAction = $event->getAction();
        if ($statusAction === 'get') {
            /** @var SensorGetStatusEvent $event */
            $eventDispatcher->dispatch(SensorGetDataEvent::NAME, $event);
        }
        /** @var SensorSetStatusEvent $event */
        $eventDispatcher->dispatch(SensorSetDataEvent::NAME, $event);
    }

}