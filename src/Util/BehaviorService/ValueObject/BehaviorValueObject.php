<?php
declare(strict_types=1);
namespace App\Util\BehaviorService\ValueObject;

use App\Entity\Sensor;

class BehaviorValueObject
{
    /** @var Sensor */
    private $sourceSensor;

    /** @var mixed */
    private $sourceSensorProperty;

    /** @var string */
    private $condition;

    /** @var string */
    private $action;

    /** @var Sensor */
    private $dependentSensor;
}