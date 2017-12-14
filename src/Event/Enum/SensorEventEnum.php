<?php
declare(strict_types=1);
namespace App\Event\Enum;

class SensorEventEnum
{
    public const SENSOR_FOUND = 'sensor.found';
    public const SENSOR_ADD = 'sensor.add';
    public const SENSOR_UPDATE = 'sensor.update';
    public const SENSOR_DELETE = 'sensor.delete';
    public const SENSOR_STATUS = 'sensor.status';
    public const SENSOR_SET_DATA = 'sensor.set.data';
    public const SENSOR_GET_DATA = 'sensor.get.data';

    public static $choices = [
        self::SENSOR_FOUND  => 'found',
        self::SENSOR_ADD    => 'add',
        self::SENSOR_UPDATE => 'update',
        self::SENSOR_DELETE => 'delete',
    ];
}