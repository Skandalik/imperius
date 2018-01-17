<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;

class JobRepository extends EntityRepository
{
    public function findByCommandName(string $commandName)
    {
        $qb = $this->createQueryBuilder('j');
        $qb->where('j.command = :commandName')
            ->setParameter('commandName', $commandName)
        ;

        try {
            return $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            return null;
        }
    }
}
