<?php
declare(strict_types=1);
namespace App\Util\SensorManager;

use App\Entity\Sensor;
use App\Util\SensorManager\Abstraction\AbstractSensorMosquittoPublisher;

class SensorMosquittoPublisher extends AbstractSensorMosquittoPublisher
{
    /**
     * @param Sensor $sensor
     * @param string $status
     */
    public function publishSetSensorStatus(Sensor $sensor, string $status = '')
    {
        $topic = $this->getTopicGenerator()->generate($sensor->getUuid(), ['status', 'set']);
        $this->publish($topic, $status);
    }

    /**
     * @param Sensor $sensor
     */
    public function publishCheckSensorStatus(Sensor $sensor)
    {
        $topic = $this->getTopicGenerator()->generate($sensor->getUuid(), ['status', 'check']);
        $this->publish($topic);
    }

    /**
     * @param Sensor $sensor
     */
    public function publishRegisteredSensorMessage(Sensor $sensor)
    {
        $topic = $this->getTopicGenerator()->generate($sensor->getUuid(), ['registered']);
        $this->publish($topic);
    }

    public function publishCheckAllSensorsStatus()
    {
        $topic = $this->getTopicGenerator()->generate('all', ['status', 'check']);
        $this->publish($topic);
    }
}