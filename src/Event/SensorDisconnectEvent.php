<?php
declare(strict_types=1);
namespace App\Event;

use App\Event\Enum\SensorEventEnum;
use Symfony\Component\EventDispatcher\Event;

class SensorDisconnectEvent extends Event
{
    const NAME = SensorEventEnum::SENSOR_DISCONNECT;

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
        $this->state = false;
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
