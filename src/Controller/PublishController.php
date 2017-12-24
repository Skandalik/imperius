<?php
declare(strict_types=1);
namespace App\Controller;

use App\Event\SensorUpdateEvent;
use App\Util\MosquittoWrapper\MosquittoPublisher;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class PublishController extends GenericController
{
    /**
     * @Route(
     *     "/sensor/{uuid}/status/set/{status}",
     *     name="sensor_set_status",
     *     requirements={
     *     "status": "\d+"
     * }
     * )
     *
     * @param MosquittoPublisher       $mosquittoPublisher
     * @param EventDispatcherInterface $dispatcher
     * @param                          $id
     * @param                          $uuid
     * @param                          $status
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function setStatusAction(
        MosquittoPublisher $mosquittoPublisher,
        EventDispatcherInterface $dispatcher,
        $uuid,
        $status
    ) {
        $topic = sprintf("sensor/%s/status/set", $uuid);

        $mosquittoPublisher->publish($topic, $status, 1, false);

        $event = new SensorUpdateEvent($uuid, $status);
        $dispatcher->dispatch(SensorUpdateEvent::NAME, $event);

        return $this->redirectToRoute('sensor');
    }
}