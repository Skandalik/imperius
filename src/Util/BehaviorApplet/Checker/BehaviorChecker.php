<?php
declare(strict_types=1);
namespace App\Util\BehaviorApplet\Checker;

use App\Entity\Behavior;
use App\Entity\Sensor;
use App\Event\SensorCheckEvent;
use App\Event\SensorUpdateEvent;
use App\Repository\BehaviorRepository;
use App\Repository\SensorRepository;
use App\Type\SensorActionsEnumType;
use App\Util\ActionExecutor\ActionExecutor;
use App\Util\ConditionChecker\ConditionChecker;
use App\Util\SensorManager\SensorMosquittoPublisher;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use const PHP_EOL;
use function count;
use function explode;
use function strval;

class BehaviorChecker
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var SensorRepository */
    private $sensorRepository;

    /** @var BehaviorRepository */
    private $behaviorRepository;

    /** @var ConditionChecker */
    private $conditionChecker;

    /** @var EventDispatcherInterface */
    private $eventDispatcher;

    /** @var ActionExecutor */
    private $actionExecutor;

    public function __construct(
        EntityManagerInterface $entityManager,
        EventDispatcherInterface $eventDispatcher,
        ConditionChecker $conditionChecker,
        ActionExecutor $actionExecutor
    ) {
        $this->entityManager = $entityManager;
        $this->eventDispatcher = $eventDispatcher;
        $this->conditionChecker = $conditionChecker;
        $this->actionExecutor = $actionExecutor;
    }

    /**
     * @param SensorCheckEvent $event
     */
    public function checkSensor(SensorCheckEvent $event)
    {
        $this->initRepositoriesClearEm();

        /** @var Sensor $sensor */
        $sensor = $this->sensorRepository->findByUuid($event->getUuid());

        $updateEvent = new SensorUpdateEvent($event->getUuid(), $event->getData());
        $this->eventDispatcher->dispatch(SensorUpdateEvent::NAME, $updateEvent);

        if (empty($sensor->getBehaviors())) {
            return;
        }

        $this->handleBehavior($sensor);
    }

    protected function initRepositoriesClearEm()
    {
        $this->flushAndClear();
        $this->sensorRepository = $this->entityManager->getRepository(Sensor::class);
        $this->behaviorRepository = $this->entityManager->getRepository(Behavior::class);
    }

    /**
     * @param Sensor $sensor
     */
    private function handleBehavior(Sensor $sensor)
    {
        /** @var Behavior $behavior */
        foreach ($sensor->getBehaviors() as $behavior) {
            $this->checkCondition($behavior);
        }
    }

    /**
     * @param Behavior $behavior
     */
    private function checkCondition(Behavior $behavior)
    {
        if (!$this->conditionChecker->checkCondition(
            $behavior->getSourceSensor(),
            $behavior->getSourceCondition(),
            strval($behavior->getSourceArgument())
        )) {
            echo "Behavior requirements didn't match for sensor " . $behavior->getSourceSensor()->getName() . PHP_EOL;
            echo PHP_EOL;

            return;
        }
        $this->actionExecutor->executeAction(
            $behavior->getDependentSensor(),
            $behavior->getDependentAction(),
            strval($behavior->getActionArgument())
        );

        $this->flushAndClear();

        return;
    }

    private function flushAndClear()
    {
        $this->entityManager->flush();
        $this->entityManager->clear();
    }
}