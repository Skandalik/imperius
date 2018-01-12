<?php
declare(strict_types=1);
namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class ScheduledBehaviorRepository extends EntityRepository
{
    /**
     * @return mixed
     */
    public function findAllNotFinished()
    {
        $qb = $this->createQueryBuilder('sb');
        $qb->where('sb.finished = false');

        return $qb->getQuery()->getResult();
    }
}