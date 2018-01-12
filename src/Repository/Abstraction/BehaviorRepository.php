<?php
declare(strict_types=1);
namespace App\Repository\Abstraction;

use App\Entity\Sensor;
use Doctrine\ORM\EntityRepository;

class BehaviorRepository extends EntityRepository
{
    /**
     * @param Sensor $sensor
     *
     * @return array
     */
    public function findAllBySensor(Sensor $sensor): array
    {
        $qb = $this->createQueryBuilder('b');
        $qb
            ->where('b.sourceSensor = :id')
            ->setParameter('id', $sensor->getId())
        ;

        return $qb->getQuery()->getResult();
    }

}