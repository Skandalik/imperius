<?php
declare(strict_types=1);
namespace App\Type\Abstraction;

use function array_flip;
use function array_keys;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Platforms\PostgreSqlPlatform;
use Doctrine\DBAL\Platforms\SQLServerPlatform;
use Doctrine\DBAL\Types\Type;

abstract class AbstractEnumType extends Type
{
    /** @var array */
    protected static $choices;

    /** @var string */
    protected $name;

    /**
     * @return array
     */
    public static function getChoices(): array
    {
        return array_flip(static::$choices);
    }

    /**
     * @return array
     */
    public static function getUnflippedChoices(): array
    {
        return static::$choices;
    }

    /**
     * @return array
     */
    protected static function getValues(): array
    {
        return array_keys(static::$choices);
    }

    /**
     * @param array            $fieldDeclaration
     * @param AbstractPlatform $platform
     *
     * @return string
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform): string
    {
        $values = implode(
            ', ',
            array_map(
                function ($value) {
                    return "'{$value}'";
                },
                static::getValues()
            )
        );
        if ($platform instanceof PostgreSqlPlatform || $platform instanceof SQLServerPlatform) {
            return sprintf('VARCHAR(255) CHECK(%s IN (%s))', $fieldDeclaration['name'], $values);
        }

        return sprintf('ENUM(%s)', $values);
    }

    /**
     * @param mixed            $value
     * @param AbstractPlatform $platform
     *
     * @return mixed
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return $value;
    }

    /**
     * @param mixed            $value
     * @param AbstractPlatform $platform
     *
     * @return mixed
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (!in_array($value, self::getValues())) {
            throw new \InvalidArgumentException("Invalid '" . $this->name . "' value.");
        }

        return $value;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param AbstractPlatform $platform
     *
     * @return bool
     */
    public function requiresSQLCommentHint(AbstractPlatform $platform)
    {
        return true;
    }
}