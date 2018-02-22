<?php
declare(strict_types=1);
namespace App\Event;

use App\Command\ValueObject\SensorValueRangeValueObject;
use App\Event\Enum\SensorEventEnum;
use Symfony\Component\EventDispatcher\Event;

class SensorFoundEvent extends Event
{
    const NAME = SensorEventEnum::SENSOR_FOUND;

    /** @var string */
    private $uuid;

    /** @var string */
    private $ip;

    /** @var bool */
    private $switchable;

    /** @var int */
    private $status;

    /** @var bool */
    private $adjustable;

    /** @var SensorValueRangeValueObject */
    private $sensorValueRange;

    /** @var bool */
    private $fetchable;

    /** @var string */
    private $dataType;

    /**
     * SensorFoundEvent constructor.
     *
     * @param string                             $uuid
     * @param string                             $ip
     * @param bool                               $fetchable
     * @param bool                               $switchable
     * @param bool                               $adjustable
     * @param int                                $status
     * @param string                             $dataType
     * @param SensorValueRangeValueObject | null $sensorValueRange
     */
    public function __construct(
        string $uuid,
        string $ip,
        bool $fetchable,
        bool $switchable,
        bool $adjustable,
        int $status,
        string $dataType,
        $sensorValueRange
    ) {
        $this->uuid = $uuid;
        $this->ip = $ip;
        $this->switchable = $switchable;
        $this->status = $status;
        $this->adjustable = $adjustable;
        $this->sensorValueRange = $sensorValueRange;
        $this->fetchable = $fetchable;
        $this->dataType = $dataType;
    }

    /**
     * @return string
     */
    public function getUuid(): string
    {
        return $this->uuid;
    }
    /**
     * @return string
     */
    public function getIp(): string
    {
        return $this->ip;
    }
    /**
     * @return bool
     */
    public function isSwitchable(): bool
    {
        return $this->switchable;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @return bool
     */
    public function isAdjustable(): bool
    {
        return $this->adjustable;
    }

    /**
     * @return SensorValueRangeValueObject
     */
    public function getSensorValueRange(): SensorValueRangeValueObject
    {
        return $this->sensorValueRange;
    }

    /**
     * @return bool
     */
    public function isFetchable(): bool
    {
        return $this->fetchable;
    }

    /**
     * @return string | null
     */
    public function getDataType()
    {
        return $this->dataType;
    }
}