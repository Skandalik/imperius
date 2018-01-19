<?php
declare(strict_types=1);
namespace App\Type;

use App\Type\Abstraction\AbstractEnumType;

class SensorStateEnumType extends AbstractEnumType
{
    const SENSOR_ACTIVE = 'active';
    const SENSOR_INACTIVE = 'inactive';
    const SENSOR_ON = 'on';
    const SENSOR_OFF = 'off';
    const SENSOR_ON_VALUE = '1';
    const SENSOR_OFF_VALUE = '0';

    protected static $choices = [
        self::SENSOR_ACTIVE    => 'active',
        self::SENSOR_INACTIVE  => 'inactive',
        self::SENSOR_ON_VALUE  => 'on',
        self::SENSOR_OFF_VALUE => 'off',
    ];

    protected $name = 'sensor_state';

}