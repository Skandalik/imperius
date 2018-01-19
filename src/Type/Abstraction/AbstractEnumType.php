<?php
declare(strict_types=1);
namespace App\Type\Abstraction;

use function array_flip;
use function array_keys;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Platforms\PostgreSqlPlatform;
use Doctrine\DBAL\Platforms\SqlitePlatform;
use Doctrine\DBAL\Platforms\SQLServerPlatform;
use Doctrine\DBAL\Types\Type;

abstract class AbstractEnumType extends Type
{
    protected $name;
    protected static $choices = [];

    public static function getChoices()
    {
        return array_keys(static::$choices);
    }

    public static function getValues()
    {
        return array_flip(static::$choices);
    }

    public static function getReadableValues()
    {
        return static::$choices;
    }

    public static function getFlippedValue($value)
    {
        return static::getValues()[$value];
    }

    public static function getValue($value)
    {
        return static::getReadableValues()[$value];
    }

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        $values = array_map(function($val) { return "'".$val."'"; }, static::getChoices());

        return "ENUM(".implode(", ", $values).")";
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return $value;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (!in_array($value, static::getChoices())) {
            throw new \InvalidArgumentException("Invalid '".$this->name."' value.");
        }
        return $value;
    }

    public function getName()
    {
        return $this->name;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform)
    {
        return true;
    }
}