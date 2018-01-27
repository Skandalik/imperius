<?php
declare(strict_types=1);
namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;

class SensorRepository extends EntityRepository
{
    public function findByUuid(string $uuid)
    {
        $qb = $this->createQueryBuilder('sensor');
        $qb->where('sensor.uuid = :uuid')
            ->setParameter('uuid', $uuid)
        ;

        try {
            return $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            return null;
        }
    }
}