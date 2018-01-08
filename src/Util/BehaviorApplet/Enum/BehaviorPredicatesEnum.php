<?php
declare(strict_types=1);
namespace App\Util\BehaviorApplet\Enum;

use function array_flip;

class BehaviorPredicatesEnum
{
    const NOT_EQUALS = 'not equals';
    const EQUALS = 'equals';
    const BIGGER_THAN = 'is bigger than';
    const SMALLER_THAN = 'is smaller than';
    const SET = 'set';
    const TURN = 'turn';
    const ON = '1';
    const OFF = '0';

    protected static $choices = [
        self::NOT_EQUALS   => '!==',
        self::EQUALS       => '===',
        self::BIGGER_THAN  => '>',
        self::SMALLER_THAN => '<',
        self::SET          => '=',
        self::TURN         => '=',
        self::ON           => '1',
        self::OFF          => '0',
    ];

    public static function findEnum(string $choice): string
    {
        return self::$choices[$choice];
    }

    public static function getChoices(): array
    {
        return array_flip(self::$choices);
    }

    public static function getUnflippedChoices(): array
    {
        return self::$choices;
    }
}