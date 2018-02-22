<?php
declare(strict_types=1);
namespace App\Command\Factory;

use App\Command\ValueObject\SensorValueRangeValueObject;

class SensorValueRangeFactory
{
    public function create(int $minimumValue, int $maximumValue)
    {
        return new SensorValueRangeValueObject($minimumValue, $maximumValue);
    }
}