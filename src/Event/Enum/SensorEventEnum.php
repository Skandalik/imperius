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

    public const SENSOR_FOUND_EVENT = 'onSensorFound';
    public const SENSOR_ADD_EVENT = 'onSensorAdd';
    public const SENSOR_UPDATE_EVENT = 'onSensorUpdate';
    public const SENSOR_DELETE_EVENT = 'onSensorDelete';
    public const SENSOR_STATUS_EVENT = 'onSensorStatus';

    public static $events = [
        self::SENSOR_FOUND  => 'found',
        self::SENSOR_ADD    => 'add',
        self::SENSOR_UPDATE => 'update',
        self::SENSOR_DELETE => 'delete',
    ];

}