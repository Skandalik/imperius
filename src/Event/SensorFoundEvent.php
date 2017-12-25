<?php
declare(strict_types=1);
namespace App\Event;

use App\Event\Enum\SensorEventEnum;
use Symfony\Component\EventDispatcher\Event;

class SensorFoundEvent extends Event
{
    const NAME = SensorEventEnum::SENSOR_FOUND;

    /** @var string  */
    private $uuid;

    /** @var string */
    private $ip;

    /** @var bool */
    private $switchable;

    /** @var int */
    private $status;

    /**
     * SensorFoundEvent constructor.
     *
     * @param string $uuid
     * @param string $ip
     * @param bool   $switchable
     * @param int    $status
     */
    public function __construct(string $uuid, string $ip, bool $switchable, int $status)
    {
        $this->uuid = $uuid;
        $this->ip = $ip;
        $this->switchable = $switchable;
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getUuid(): string
    {
        return $this->uuid;
    }

    /**
     * @param string $uuid
     *
     * @return SensorFoundEvent
     */
    public function setUuid(string $uuid): SensorFoundEvent
    {
        $this->uuid = $uuid;

        return $this;
    }

    /**
     * @return string
     */
    public function getIp(): string
    {
        return $this->ip;
    }

    /**
     * @param string $ip
     *
     * @return SensorFoundEvent
     */
    public function setIp(string $ip): SensorFoundEvent
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * @return bool
     */
    public function isSwitchable(): bool
    {
        return $this->switchable;
    }

    /**
     * @param bool $switchable
     *
     * @return SensorFoundEvent
     */
    public function setSwitchable(bool $switchable): SensorFoundEvent
    {
        $this->switchable = $switchable;

        return $this;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @param int $status
     *
     * @return SensorFoundEvent
     */
    public function setStatus(int $status): SensorFoundEvent
    {
        $this->status = $status;

        return $this;
    }

}