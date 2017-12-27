<?php
declare(strict_types=1);
namespace App\Command\ValueObject;

class SensorValueRangeValueObject
{
    /** @var int */
    private $minimumValue;

    /** @var int */
    private $maximumValue;

    /**
     * SensorValueRangeValueObject constructor.
     *
     * @param int $minimumValue
     * @param int $maximumValue
     */
    public function __construct(int $minimumValue, int $maximumValue)
    {
        $this->minimumValue = $minimumValue;
        $this->maximumValue = $maximumValue;
    }

    /**
     * @return int
     */
    public function getMinimumValue(): int
    {
        return $this->minimumValue;
    }

    /**
     * @return int
     */
    public function getMaximumValue(): int
    {
        return $this->maximumValue;
    }

}