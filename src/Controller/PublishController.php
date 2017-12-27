<?php
declare(strict_types=1);
namespace App\Controller;

use App\Event\SensorUpdateEvent;
use App\Util\MosquittoWrapper\MosquittoPublisher;
use App\Util\TopicGenerator\TopicGenerator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class PublishController extends GenericController
{
    /**
     * @Route(
     *     "/sensor/{uuid}/status/set/{status}",
     *     name="sensor_set_status",
     *     options={
     *     "expose"=true
     *     },
     *     requirements={
     *     "status": "\d+"
     * }
     * )
     *
     * @param MosquittoPublisher       $mosquittoPublisher
     * @param EventDispatcherInterface $dispatcher
     * @param TopicGenerator           $topicGenerator
     * @param                          $uuid
     * @param                          $status
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function setStatusAction(
        MosquittoPublisher $mosquittoPublisher,
        EventDispatcherInterface $dispatcher,
        TopicGenerator $topicGenerator,
        $uuid,
        $status
    ) {
        $topic = $topicGenerator->generate($uuid, ['status', 'set']);

        $mosquittoPublisher->publish($topic, $status);

        $event = new SensorUpdateEvent($uuid, $status);
        $dispatcher->dispatch(SensorUpdateEvent::NAME, $event);

        return $this->redirectToRoute('sensor');
    }
}