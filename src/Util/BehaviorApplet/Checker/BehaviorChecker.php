<?php
declare(strict_types=1);
namespace App\Util\BehaviorApplet\Checker;

use App\Entity\Behavior;
use App\Entity\Sensor;
use App\Event\SensorUpdateEvent;
use App\Repository\BehaviorRepository;
use App\Repository\SensorRepository;
use App\Util\BehaviorApplet\Enum\BehaviorPredicatesEnum;
use App\Util\MosquittoWrapper\MosquittoPublisher;
use App\Util\TopicGenerator\TopicGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use function intval;

class BehaviorChecker
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var SensorRepository */
    private $sensorRepository;

    /** @var BehaviorRepository */
    private $behaviorRepository;

    /** @var PropertyAccessorInterface */
    private $propertyAccessor;

    /** @var EventDispatcherInterface */
    private $eventDispatcher;

    /** @var TopicGenerator */
    private $topicGenerator;

    /** @var MosquittoPublisher */
    private $mosquittoPublisher;

    public function __construct(
        EntityManagerInterface $entityManager,
        PropertyAccessorInterface $propertyAccessor,
        EventDispatcherInterface $eventDispatcher,
        TopicGenerator $topicGenerator,
        MosquittoPublisher $mosquittoPublisher
    ) {
        $this->entityManager = $entityManager;
        $this->sensorRepository = $entityManager->getRepository(Sensor::class);
        $this->behaviorRepository = $entityManager->getRepository(Behavior::class);
        $this->propertyAccessor = $propertyAccessor;
        $this->eventDispatcher = $eventDispatcher;
        $this->topicGenerator = $topicGenerator;
        $this->mosquittoPublisher = $mosquittoPublisher;
    }

    /**
     * @param SensorUpdateEvent $event
     */
    public function listenOnSensorUpdate(SensorUpdateEvent $event)
    {
        /** @var Sensor $sensor */
        $sensor = $this->sensorRepository->findByUuid($event->getUuid());

        if (empty($sensor->getBehaviors()->getValues())) {
            return;
        }

        echo "Checking behavior";
        $this->handleBehavior($sensor);
    }

    private function handleBehavior(Sensor $sensor)
    {
        /** @var Behavior $behavior */
        foreach ($sensor->getBehaviors() as $behavior) {
            $this->checkCondition($sensor, $behavior);
        }
    }

    private function checkCondition(Sensor $sensor, Behavior $behavior)
    {
        $sourceValue = $this->getPropertyValue($sensor, $behavior->getSourceProperty());
        $dependentValue = $this->getPropertyValue($behavior->getDependentSensor(), $behavior->getSourceProperty());
        $predicate = $behavior->getPredicate();
        $argument = intval($behavior->getPredicateArgument());

        $enum = BehaviorPredicatesEnum::findEnum($predicate);

        if (!$this->checkStatementWithEval($this->convertToInt($sourceValue) . ' ' . BehaviorPredicatesEnum::findEnum($predicate) . ' ' . $argument)) {
            echo "Behavior requirements didn't match.";
            return;
        }

        echo "Behavior requirements matched. Setting another sensor.";
        //TODO te trzy metody są używane też w SensorController. Może zrobić to lepiej?
        $uuid = $behavior->getDependentSensor()->getUuid();
        $topic = $this->topicGenerator->generate($uuid, ['status', 'set']);
        $this->mosquittoPublisher->publish($topic, $behavior->getActionArgument());
        $event = new SensorUpdateEvent($uuid, $behavior->getActionArgument());
        $this->eventDispatcher->dispatch(SensorUpdateEvent::NAME, $event);
    }

    private function checkStatementWithEval(string $statement)
    {
        return eval('return ' . $statement . ';');
    }

    /**
     * @param Sensor $sensor
     * @param string $property
     *
     * @return mixed
     */
    private function getPropertyValue(Sensor $sensor, string $property)
    {
        return $this->propertyAccessor->getValue($sensor, $property);
    }

    /**
     * @param $value
     *
     * @return bool|int
     */
    private function convertToInt($value)
    {
        return intval($value);
    }
}