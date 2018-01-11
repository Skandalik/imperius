<?php
declare(strict_types=1);
namespace App\Util\ActionExecutor;

use App\Entity\Sensor;
use App\Type\SensorActionsEnumType;
use App\Util\ActionExecutor\Abstraction\AbstractActionValueObject;
use App\Util\ActionExecutor\Factory\ActionValueObjectFactory;
use App\Util\SensorManager\SensorMosquittoPublisher;
use Doctrine\ORM\EntityManagerInterface;

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
        $action = $this->actionValueObjectFactory->createCondition(
            explode(' ', SensorActionsEnumType::getValue($actionData)),
            $argument
        );

        $this->sensorMosquittoPublisher->setSensorStatus($sensor, $action->getArgument());
    }
}