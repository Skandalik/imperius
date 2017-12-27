<?php
declare(strict_types=1);
namespace App\Factory;

use App\Entity\Sensor;

class SensorFactory
{
    public function create()
    {
        return new Sensor();
    }
}