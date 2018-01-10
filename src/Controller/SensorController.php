<?php
declare(strict_types=1);
namespace App\Controller;

use App\Entity\Sensor;
use App\Event\SensorUpdateEvent;
use App\Form\SensorType;
use App\Type\SensorStateEnumType;
use App\Util\MosquittoWrapper\MosquittoPublisher;
use App\Util\TopicGenerator\TopicGenerator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use function strval;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Response;

class SensorController extends GenericController
{
    protected $entityClass = Sensor::class;

    protected $formType = SensorType::class;

    /**
     * @Route(
     *     name="set_status",
     *     path="/api/sensors/{id}/status/set/{status}",
     *     defaults={
     *          "_api_item_operation_name"="set_status"
     *     }
     * )
     * @Method("PUT")
     * @param MosquittoPublisher       $mosquittoPublisher
     * @param EventDispatcherInterface $dispatcher
     * @param TopicGenerator           $topicGenerator
     * @param                          $id
     * @param                          $status
     *
     * @return Response
     */
    public function setStatusAction(
        MosquittoPublisher $mosquittoPublisher,
        EventDispatcherInterface $dispatcher,
        TopicGenerator $topicGenerator,
        $id,
        $status
    ) {
        /** @var Sensor $sensor */
        $sensor = $this->getRepository()->find($id);
        $uuid = $sensor->getUuid();
        $topic = $topicGenerator->generate($uuid, ['status', 'set']);
        $mosquittoPublisher->publish($topic, $status);

        return $this->serializeObject($sensor);
    }

    /**
     * @Route(
     *     name="set_state",
     *     path="/api/sensors/{id}/set/{status}",
     *     defaults={
     *          "_api_item_operation_name"="set_state"
     *     }
     * )
     * @Method("PUT")
     * @param MosquittoPublisher       $mosquittoPublisher
     * @param EventDispatcherInterface $dispatcher
     * @param TopicGenerator           $topicGenerator
     * @param                          $id
     * @param                          $status
     *
     * @return Response
     */
    public function setStateAction(
        MosquittoPublisher $mosquittoPublisher,
        EventDispatcherInterface $dispatcher,
        TopicGenerator $topicGenerator,
        $id,
        $status
    ) {
        /** @var Sensor $sensor */
        $sensor = $this->getRepository()->find($id);
        $uuid = $sensor->getUuid();
        $topic = $topicGenerator->generate($uuid, ['status', 'set']);
        $mosquittoPublisher->publish($topic, strval(SensorStateEnumType::getFlippedValue($status)));

        return $this->serializeObject($sensor);
    }

    /**
     * @Route(
     *     name="check_status",
     *     path="/api/sensors/{id}/status/check",
     *     requirements={"id"="\d+"},
     *     defaults={
     *          "_api_item_operation_name"="check_status"
     *     }
     * )
     * @Method("GET")
     * @param MosquittoPublisher       $mosquittoPublisher
     * @param TopicGenerator           $topicGenerator
     * @param                          $id
     *
     * @return Response
     */
    public function checkStatusAction(
        MosquittoPublisher $mosquittoPublisher,
        TopicGenerator $topicGenerator,
        $id
    ) {
        /** @var Sensor $sensor */
        $sensor = $this->getRepository()->find($id);
        $arr = $sensor->getBehaviors();
        $uuid = $sensor->getUuid();
        $checkStatusTopic = $topicGenerator->generate($uuid, ['status', 'check']);
        $mosquittoPublisher->publish($checkStatusTopic);

        return $this->serializeObject($sensor);
    }

    /**
     * @Route(
     *     name="check_bulk_status",
     *     path="/api/sensors/all/status/check",
     *     defaults={
     *          "_api_item_operation_name"="check_bulk_status"
     *     }
     * )
     * @Method("GET")
     * @param MosquittoPublisher $mosquittoPublisher
     * @param TopicGenerator     $topicGenerator
     *
     * @return Response
     */
    public function checkBulkStatusAction(
        MosquittoPublisher $mosquittoPublisher,
        TopicGenerator $topicGenerator
    ) {
        $checkStatusTopic = $topicGenerator->generate('all', ['status', 'check']);

        $mosquittoPublisher->publish($checkStatusTopic);

        return $this->serializeObject($this->getRepository()->findAll());
    }

    /**
     * @param mixed $sensor
     *
     * @return Response
     */
    private function serializeObject($sensor): Response
    {
        $response = new Response($this->getSerializer()->serialize($sensor, 'json'));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}
