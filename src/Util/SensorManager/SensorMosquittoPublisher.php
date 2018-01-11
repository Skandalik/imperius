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
    public function setSensorStatus(Sensor $sensor, string $status = '')
    {
        $topic = $this->getTopicGenerator()->generate($sensor->getUuid(), ['status', 'set']);
        $this->publish($topic, $status);
    }
}