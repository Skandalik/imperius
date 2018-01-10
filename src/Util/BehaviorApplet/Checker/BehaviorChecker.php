<?php
declare(strict_types=1);
namespace App\Util\BehaviorApplet\Checker;

use App\Entity\Behavior;
use App\Entity\Sensor;
use App\Event\SensorUpdateEvent;
use App\Repository\BehaviorRepository;
use App\Repository\SensorRepository;
use App\Type\SensorActionsEnumType;
use App\Type\SensorConditionsEnumType;
use App\Util\BehaviorApplet\DataObject\BehaviorDataObject;
use App\Util\BehaviorApplet\Enum\BehaviorPredicatesEnum;
use App\Util\BehaviorApplet\Factory\BehaviorDataObjectFactory;
use App\Util\MosquittoWrapper\MosquittoPublisher;
use App\Util\TopicGenerator\TopicGenerator;
use function count;
use Doctrine\ORM\EntityManagerInterface;
use function explode;
use const PHP_EOL;
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

    /**
     * @var BehaviorDataObjectFactory
     */
    private $behaviorDataObjectFactory;

    public function __construct(
        EntityManagerInterface $entityManager,
        PropertyAccessorInterface $propertyAccessor,
        EventDispatcherInterface $eventDispatcher,
        TopicGenerator $topicGenerator,
        MosquittoPublisher $mosquittoPublisher,
        BehaviorDataObjectFactory $behaviorDataObjectFactory
    ) {
        $this->entityManager = $entityManager;
        $this->sensorRepository = $entityManager->getRepository(Sensor::class);
        $this->behaviorRepository = $entityManager->getRepository(Behavior::class);
        $this->propertyAccessor = $propertyAccessor;
        $this->eventDispatcher = $eventDispatcher;
        $this->topicGenerator = $topicGenerator;
        $this->mosquittoPublisher = $mosquittoPublisher;
        $this->behaviorDataObjectFactory = $behaviorDataObjectFactory;
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

        echo  PHP_EOL;
        echo "++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++" . PHP_EOL;
        echo "Checking behavior for sensor " . $sensor->getName() . PHP_EOL. PHP_EOL;
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
        $conditions = explode(' ', SensorConditionsEnumType::getValue($behavior->getSourceCondition()));
        $actions = explode(' ', SensorActionsEnumType::getValue($behavior->getDependentAction()));

        if (count($conditions) === 2) {
            $conditions[] = $behavior->getSourceArgument();
        }
        if (count($actions) === 2) {
            $actions[] = $behavior->getActionArgument();
        }

        $conditionBehavior = $this->behaviorDataObjectFactory->create($conditions);
        $actionBehavior = $this->behaviorDataObjectFactory->create($actions);

        $propertyData = $this->propertyAccessor->getValue(
            $behavior->getSourceSensor(),
            $conditionBehavior->getProperty()
        );

        if (!$this->checkStatementWithEval(
            intval($propertyData) . ' ' . $conditionBehavior->getExpression() . ' ' . $conditionBehavior->getArgument()
        )) {
            echo "Behavior requirements didn't match for sensor " . $behavior->getSourceSensor()->getName() . PHP_EOL;
            echo "Condition: " . $conditionBehavior->getProperty() . " " . $conditionBehavior->getExpression() . " " . $conditionBehavior ->getArgument() . PHP_EOL;
            echo "Action: " . $conditionBehavior->getProperty() . " " . $conditionBehavior->getExpression() . " " . $conditionBehavior ->getArgument() . PHP_EOL;
            echo "On sensor " . $behavior->getDependentSensor()->getName() . PHP_EOL;
            echo  PHP_EOL;

            return;
        }

        echo "Behavior requirements matched for sensor " . $behavior->getSourceSensor()->getName() . PHP_EOL;
        echo "Condition: " . $conditionBehavior->getProperty() . " " . $conditionBehavior->getExpression() . " " . $conditionBehavior ->getArgument() . PHP_EOL;
        echo "Action: " . $actionBehavior->getProperty() . " " . $actionBehavior->getExpression() . " " . $actionBehavior ->getArgument() . PHP_EOL;
        echo "On sensor " . $behavior->getDependentSensor()->getName() . PHP_EOL;
        //TODO te trzy metody są używane też w SensorController. Może zrobić to lepiej?
        $uuid = $behavior->getDependentSensor()->getUuid();
        $topic = $this->topicGenerator->generate($uuid, ['status', 'set']);
        $this->mosquittoPublisher->publish($topic, $actionBehavior->getArgument());
        $event = new SensorUpdateEvent($uuid, $actionBehavior->getArgument());
        $this->eventDispatcher->dispatch(SensorUpdateEvent::NAME, $event);
        echo "++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++" . PHP_EOL;
        echo  PHP_EOL;
    }

    private function checkStatementWithEval(string $statement)
    {
        return eval('return ' . $statement . ';');
    }
}