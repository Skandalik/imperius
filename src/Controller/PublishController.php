<?php
declare(strict_types=1);
namespace App\Controller;

use App\Event\SensorFoundEvent;
use App\Util\MosquittoWrapper\MosquittoPublisher;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class PublishController extends GenericController
{
    public function publishAction(MosquittoPublisher $mosquittoPublisher, EventDispatcherInterface $dispatcher, $topic, $payload)
    {
        $mosquittoPublisher->publish($topic, $payload, 1, false);

        $event = new SensorFoundEvent('testId');
        $dispatcher->dispatch(SensorFoundEvent::NAME, $event);

        return $this->redirectToRoute('sensor');
    }
}