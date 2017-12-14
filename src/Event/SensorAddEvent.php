<?php
declare(strict_types=1);
namespace App\Event;

use App\Entity\Sensor;
use App\Event\Enum\SensorEventEnum;
use Symfony\Component\EventDispatcher\Event;

class SensorAddEvent extends Event
{
    const NAME = SensorEventEnum::SENSOR_ADD;

    /** @var Sensor */
   private $entity;

   /** @var bool */
   private $fromScan;

    /**
     * SensorAddEvent constructor.
     *
     * @param Sensor $entity
     * @param bool   $fromScan
     */
    public function __construct(Sensor $entity, bool $fromScan = true)
    {
        $this->entity = $entity;
        $this->fromScan = $fromScan;
    }

    /**
     * @return Sensor
     */
    public function getEntity(): Sensor
    {
        return $this->entity;
    }

    /**
     * @param Sensor $entity
     *
     * @return SensorAddEvent
     */
    public function setEntity(Sensor $entity): SensorAddEvent
    {
        $this->entity = $entity;

        return $this;
    }

    /**
     * @return bool
     */
    public function isFromScan(): bool
    {
        return $this->fromScan;
    }

    /**
     * @param bool $fromScan
     */
    public function setFromScan(bool $fromScan)
    {
        $this->fromScan = $fromScan;
    }
}