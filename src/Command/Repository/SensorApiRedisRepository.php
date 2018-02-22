<?php
declare(strict_types=1);
namespace App\Command\Repository;

use App\Entity\Sensor;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializerInterface;
use Redis;

class SensorApiRedisRepository
{
    /** @var Redis */
    private $redis;

    /** @var EntityManagerInterface */
    private $entityManager;

    /**@var SerializerInterface */
    private $serializer;

    /**
     * SensorApiRedisRepository constructor.
     *
     * @param Redis                  $redis
     * @param EntityManagerInterface $entityManager
     * @param SerializerInterface    $serializer
     */
    public function __construct(Redis $redis, EntityManagerInterface $entityManager, SerializerInterface $serializer)
    {
        $this->redis = $redis;
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
    }

    public function generateCacheFromDatabase()
    {
        $sensors = $this->getRepository()->findAll();
        foreach ($sensors as $sensor) {
            $json = $this->serializer->serialize($sensor, 'json');
            $this->redis->set($sensor->getId(), $json);
        }
    }

    /**
     * @return array
     */
    public function getAll(): array
    {
        $keys = $this->redis->keys('*');
        asort($keys);

        $sensors = [];

        foreach ($keys as $key) {
            $sensors[] = $this->serializer->deserialize($this->redis->get($key), Sensor::class, 'json');
        }

        return $sensors;
    }

    private function getRepository()
    {
        return $this->entityManager->getRepository(Sensor::class);
    }
}