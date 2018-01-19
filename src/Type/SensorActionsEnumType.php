<?php
declare(strict_types=1);
namespace App\Type;

use App\Type\Abstraction\AbstractEnumType;

class SensorActionsEnumType extends AbstractEnumType
{
    const TURN_OFF = 'turn_off';

    const TURN_ON = 'turn_on';

    const SET = 'set';

    protected static $choices = [
        self::TURN_OFF => 'active = off',
        self::TURN_ON  => 'active = on',
        self::SET      => 'status =',
    ];

    protected $name = 'sensor_actions_enum';
}