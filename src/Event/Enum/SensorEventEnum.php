<?php
declare(strict_types=1);
namespace App\Event\Enum;

class SensorEventEnum
{
    public const SENSOR_FOUND = 'sensor.found';
    public const SENSOR_ADD = 'sensor.add';
    public const SENSOR_UPDATE = 'sensor.update';
    public const SENSOR_DELETE = 'sensor.delete';
    public const SENSOR_DISCONNECT = 'sensor.disconnect';
    public const SENSOR_CONNECT = 'sensor.connect';
    public const SENSOR_CHECK = 'sensor.check';

    public const SENSOR_FOUND_EVENT = 'onSensorFound';
    public const SENSOR_ADD_EVENT = 'onSensorAdd';
    public const SENSOR_UPDATE_EVENT = 'onSensorUpdate';
    public const SENSOR_DELETE_EVENT = 'onSensorDelete';
    public const SENSOR_STATUS_EVENT = 'onSensorStatus';
    public const SENSOR_DISCONNECT_EVENT = 'onSensorDisconnect';
    public const SENSOR_CHECK_EVENT = 'onSensorCheck';

    public static $events = [
        self::SENSOR_FOUND      => 'found',
        self::SENSOR_ADD        => 'add',
        self::SENSOR_UPDATE     => 'update',
        self::SENSOR_DELETE     => 'delete',
        self::SENSOR_DISCONNECT => 'disconnect',
        self::SENSOR_CONNECT    => 'connect',
        self::SENSOR_CHECK      => 'check',
    ];

}