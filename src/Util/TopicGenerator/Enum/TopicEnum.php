<?php
declare(strict_types=1);
namespace App\Util\TopicGenerator\Enum;

class TopicEnum
{
    public const SENSOR_TOPIC_PREFIX = 'sensor';
    public const SENSOR_REGISTER = self::SENSOR_TOPIC_PREFIX . '/register';
    public const SENSOR_LAST_WILL = self::SENSOR_TOPIC_PREFIX . '/last-will';
    public const SENSOR_STATUS_RESPONSE = self::SENSOR_TOPIC_PREFIX . '/+/status/response';
}