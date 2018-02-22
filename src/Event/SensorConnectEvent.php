<?php
declare(strict_types=1);
namespace App\Event;

use App\Event\Enum\SensorEventEnum;
use Symfony\Component\EventDispatcher\Event;

class SensorConnectEvent extends Event
{
    const NAME = SensorEventEnum::SENSOR_CONNECT;

    /** @var string */
    protected $uuid;

    /** @var string */
    protected $state;

    /**
     * @param string $uuid
     */
    public function __construct(string $uuid)
    {
        $this->uuid = $uuid;
        $this->state = true;
    }

    /**
     * @return string
     */
    public function getUuid(): string
    {
        return $this->uuid;
    }

    /**
     * @return bool
     */
    public function getState(): bool
    {
        return $this->state;
    }
}
