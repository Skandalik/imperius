<?php
declare(strict_types=1);
namespace App\Util\ActionExecutor;

use App\Entity\Sensor;
use App\Type\SensorActionsEnumType;
use App\Type\SensorStateEnumType;
use App\Util\ActionExecutor\Abstraction\AbstractActionValueObject;
use App\Util\ActionExecutor\Factory\ActionValueObjectFactory;
use App\Util\SensorManager\SensorMosquittoPublisher;
use Doctrine\ORM\EntityManagerInterface;
use function in_array;
use function is_int;

class ActionExecutor
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
     * @param Sensor $sensor
     * @param string  $actionData
     * @param string $argument
     */
    public function executeAction(Sensor $sensor, string $actionData, string $argument = '')
    {
        $val = SensorActionsEnumType::getValue($actionData);

        $action = $this->actionValueObjectFactory->createAction(
            explode(' ', $val),
            $argument
        );

        $status = $action->getArgument();
        $choices = SensorStateEnumType::getReadableValues();
        if (in_array($action->getArgument(), $choices)) {
            $status = strval(SensorStateEnumType::getFlippedValue($action->getArgument()));
        }
        $this->sensorMosquittoPublisher->setSensorStatus($sensor, $status);
    }
}