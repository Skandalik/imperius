<?php
declare(strict_types=1);
namespace App\Util\ActionManager;

use App\Entity\Behavior\Abstraction\BehaviorInterface;
use App\Type\SensorActionsEnumType;
use App\Type\SensorStateEnumType;
use App\Util\ActionManager\Abstraction\AbstractActionValueObject;
use App\Util\ActionManager\Factory\ActionValueObjectFactory;
use App\Util\SensorManager\SensorMosquittoPublisher;
use function in_array;
use function strval;

class ActionManager
{
    /** @var SensorMosquittoPublisher */
    private $sensorMosquittoPublisher;

    /** @var ActionValueObjectFactory */
    private $actionValueObjectFactory;

    public function __construct(
        SensorMosquittoPublisher $sensorMosquittoPublisher,
        ActionValueObjectFactory $actionValueObjectFactory
    ) {
        $this->sensorMosquittoPublisher = $sensorMosquittoPublisher;
        $this->actionValueObjectFactory = $actionValueObjectFactory;
    }

    /**
     * @param BehaviorInterface $behavior
     */
    public function performAction(BehaviorInterface $behavior)
    {
        $action = $this->actionValueObjectFactory->create(
            explode(' ', SensorActionsEnumType::getValue($behavior->getAction())),
            $behavior->getActionArgument()
        );

        $this->sensorMosquittoPublisher->publishSetSensorStatus($behavior->getActionSensor(), $this->getStatus($action));
    }

    /**
     * @param AbstractActionValueObject $actionValueObject
     *
     * @return string
     */
    private function getStatus(AbstractActionValueObject $actionValueObject): string
    {
        if (in_array($actionValueObject->getArgument(), SensorStateEnumType::getReadableValues())) {
            return strval(SensorStateEnumType::getFlippedValue($actionValueObject->getArgument()));
        }

        return strval($actionValueObject->getArgument());
    }
}