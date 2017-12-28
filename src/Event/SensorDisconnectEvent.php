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

    /** @var bool */
    protected $active;

    /**
     * @param string $uuid
     */
    public function __construct(string $uuid)
    {
        $this->uuid = $uuid;
        $this->active = false;
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
    public function getActive(): bool
    {
        return $this->active;
    }
}
