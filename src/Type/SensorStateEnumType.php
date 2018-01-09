<?php
declare(strict_types=1);
namespace App\Type;

use App\Type\Abstraction\AbstractEnumType;

class SensorStateEnumType extends AbstractEnumType
{
    const SENSOR_ACTIVE = 'active';

    const SENSOR_INACTIVE = 'inactive';

    protected static $choices = [
        self::SENSOR_ACTIVE => 'IS ACTIVE',
        self::SENSOR_INACTIVE => 'IS ACTIVE',
    ];

    protected $name = 'sensor_state';

}