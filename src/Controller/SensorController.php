<?php
declare(strict_types=1);
namespace App\Controller;

use App\Entity\Sensor;
use App\Form\SensorType;

class SensorController extends GenericController
{
    protected $entityClass = Sensor::class;

    protected $formType = SensorType::class;

}