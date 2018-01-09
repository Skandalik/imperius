<?php
declare(strict_types=1);
namespace App\Event;

use App\Event\Enum\SensorEventEnum;
use App\Type\SensorStateEnumType;
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
        $this->state = SensorStateEnumType::SENSOR_INACTIVE;
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
    public function getState(): string
    {
        return $this->state;
    }
}
