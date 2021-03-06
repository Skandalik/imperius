<?php
declare(strict_types=1);
namespace App\Event;

use App\Event\Enum\SensorEventEnum;
use Symfony\Component\EventDispatcher\Event;

class SensorRegisteredEvent extends Event
{
    const NAME = SensorEventEnum::SENSOR_UPDATE;

    /** @var string $id */
    protected $id;

    /** @var string $data */
    protected $data;

    /** @var string */
    private $action;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId(string $id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getData(): string
    {
        return $this->data;
    }

    /**
     * @param string $data
     */
    public function setData(string $data)
    {
        $this->data = $data;
    }

    /**
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
    }
}