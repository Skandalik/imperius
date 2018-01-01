<?php
declare(strict_types=1);
namespace App\Controller;

use App\Command\Repository\SensorApiRedisRepository;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;

class SensorApiController extends FOSRestController
{
    /**
     * @Rest\Get(path="/api/sensor")
     */
    public function getAllAction()
    {
        $repository = $this->getDoctrine()->getRepository('App:Sensor');

        return $repository->findAll();
    }

    /**
     * @Rest\Get(path="/api/cache/sensor")
     */
    public function getAllCachedAction(SensorApiRedisRepository $sensorApiRedisRepository)
    {
        return $sensorApiRedisRepository->getAll();
    }

    /**
     * @Rest\Get(path="/api/cache/sensor/create")
     */
    public function createSensorCacheAction(SensorApiRedisRepository $sensorApiRedisRepository)
    {
        $sensorApiRedisRepository->generateCacheFromDatabase();
    }
}
