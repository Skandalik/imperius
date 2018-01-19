<?php
declare(strict_types=1);
namespace App\Util\DateSupplier\Enum;

use App\Type\Abstraction\AbstractEnumType;

class DateSupplierTimeEnum extends AbstractEnumType
{
    const MINUTE = 'minute';
    const MINUTES = 'minutes';
    const HOUR = 'hour';
    const HOURS = 'hours';

    protected static $choices = [
        self::MINUTE,
        self::MINUTES,
        self::HOUR,
        self::HOURS
    ];
}