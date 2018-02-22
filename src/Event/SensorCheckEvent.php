<?php
declare(strict_types=1);
namespace App\Event;

use App\Event\Enum\SensorEventEnum;
use Symfony\Component\EventDispatcher\Event;

class SensorCheckEvent extends Event
{
    const NAME = SensorEventEnum::SENSOR_CHECK;

    /** @var string */
    protected $uuid;

    /** @var string */
    protected $data;

    /**
     * @param string $uuid
     * @param string $data
     */
    public function __construct(string $uuid, string $data)
    {
        $this->uuid = $uuid;
        $this->data = $data;
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
    public function getData(): string
    {
        return $this->data;
    }
}