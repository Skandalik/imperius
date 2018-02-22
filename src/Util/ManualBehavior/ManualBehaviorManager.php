<?php
declare(strict_types=1);
namespace App\Util\ManualBehavior;

use App\Entity\ManualBehavior;
use App\Entity\Sensor;
use App\Event\SensorCheckEvent;
use App\Event\SensorUpdateEvent;
use App\Repository\ManualBehaviorRepository;
use App\Repository\SensorRepository;
use App\Util\ActionManager\ActionManager;
use App\Util\ConditionChecker\ConditionChecker;
use App\Util\LogHelper\LogContextEnum;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use function is_null;

class ManualBehaviorManager
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var SensorRepository */
    private $sensorRepository;

    /** @var ManualBehaviorRepository */
    private $behaviorRepository;

    /** @var ConditionChecker */
    private $conditionChecker;

    /** @var EventDispatcherInterface */
    private $eventDispatcher;

    /** @var ActionManager */
    private $actionManager;

    /** @var LoggerInterface */
    private $logger;

    /**
     * ManualBehaviorManager constructor.
     *
     * @param EntityManagerInterface   $entityManager
     * @param EventDispatcherInterface $eventDispatcher
     * @param ConditionChecker         $conditionChecker
     * @param ActionManager            $actionExecutor
     * @param LoggerInterface          $logger
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        EventDispatcherInterface $eventDispatcher,
        ConditionChecker $conditionChecker,
        ActionManager $actionExecutor,
        LoggerInterface $logger
    ) {
        $this->entityManager = $entityManager;
        $this->eventDispatcher = $eventDispatcher;
        $this->conditionChecker = $conditionChecker;
        $this->actionManager = $actionExecutor;
        $this->logger = $logger;
    }

    /**
     * @param SensorCheckEvent $event
     */
    public function checkSensor(SensorCheckEvent $event)
    {
        $this->initRepositoriesClearEm();

        /** @var Sensor $sensor */
        $sensor = $this->sensorRepository->findByUuid($event->getUuid());

        if (is_null($sensor)) {
            return;
        }

        $updateEvent = new SensorUpdateEvent($event->getUuid(), $event->getData());
        $this->eventDispatcher->dispatch(SensorUpdateEvent::NAME, $updateEvent);

        if (empty($sensor->getManualBehaviors())) {
            return;
        }

        $this->handleBehavior($sensor);
    }

    /**
     * @param Sensor $sensor
     */
    private function handleBehavior(Sensor $sensor)
    {
        /** @var ManualBehavior $behavior */
        foreach ($sensor->getManualBehaviors() as $behavior) {
            $this->checkCondition($behavior);
        }
    }

    /**
     * @param ManualBehavior $behavior
     */
    private function checkCondition(ManualBehavior $behavior)
    {
        if (!$this->conditionChecker->checkCondition($behavior)) {
            $this->logger->info(
                sprintf(
                    'Manual behavior %s for sensor %s didn\'t matched requirements!',
                    $behavior->getId(),
                    $behavior->getSensor()->getUuid()
                ),
                [
                    LogContextEnum::MANUAL_BEHAVIOR_ID => $behavior->getId(),
                    LogContextEnum::SENSOR_ID          => $behavior->getSensor()->getId(),
                    LogContextEnum::SENSOR_UUID        => $behavior->getSensor()->getUuid(),
                    LogContextEnum::ACTION_SENSOR_ID   => $behavior->getActionSensor()->getId(),
                    LogContextEnum::ACTION_SENSOR_UUID => $behavior->getActionSensor()->getUuid(),
                ]
            );

            return;
        }

        $this->logger->info(
            sprintf(
                'Manual behavior %s for sensor %s matched requirements!',
                $behavior->getId(),
                $behavior->getSensor()->getUuid()
            ),
            [
                LogContextEnum::MANUAL_BEHAVIOR_ID => $behavior->getId(),
                LogContextEnum::SENSOR_ID          => $behavior->getSensor()->getId(),
                LogContextEnum::SENSOR_UUID        => $behavior->getSensor()->getUuid(),
                LogContextEnum::ACTION_SENSOR_ID   => $behavior->getActionSensor()->getId(),
                LogContextEnum::ACTION_SENSOR_UUID => $behavior->getActionSensor()->getUuid(),
            ]
        );

        $this->actionManager->performAction($behavior);

        $this->flushAndClear();

        return;
    }

    private function flushAndClear()
    {
        $this->entityManager->flush();
        $this->entityManager->clear();
    }

    protected function initRepositoriesClearEm()
    {
        $this->flushAndClear();
        $this->sensorRepository = $this->entityManager->getRepository(Sensor::class);
        $this->behaviorRepository = $this->entityManager->getRepository(ManualBehavior::class);
    }
}