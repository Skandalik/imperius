<?php
declare(strict_types=1);
namespace App\Type;

use App\Type\Abstraction\AbstractEnumType;

class SensorConditionsEnumType extends AbstractEnumType
{
    const IS_OFF = 'is_off';

    const IS_ON = 'is_on';

    const EQUALS = 'equals';

    const NOT_EQUALS = 'not_equals';

    const BIGGER_THAN = 'bigger_than';

    const SMALLER_THAN = 'smaller_than';

    const BIGGER_EQUALS_THAN = 'bigger_equals_than';

    const SMALLER_EQUALS_THAN = 'smaller_equals_than';

    protected static $choices = [
        self::IS_OFF              => 'active === 0',
        self::IS_ON               => 'active === 1',
        self::EQUALS              => 'status ===',
        self::NOT_EQUALS          => 'status !==',
        self::BIGGER_THAN         => 'status >',
        self::SMALLER_THAN        => 'status <',
        self::BIGGER_EQUALS_THAN  => 'status >=',
        self::SMALLER_EQUALS_THAN => 'status <=',
    ];

    protected $name = 'sensor_conditions_enum';
}