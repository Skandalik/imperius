<?php
declare(strict_types=1);
namespace App\Util\TopicGenerator\Enum;

class TopicEnum
{
    public const SENSOR_TOPIC_PREFIX = 'sensor';
    public const SENSOR_REGISTER = self::SENSOR_TOPIC_PREFIX . '/register';
}