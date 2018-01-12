<?php
declare(strict_types=1);
namespace App\Repository;

use App\Repository\Abstraction\BehaviorRepository;

class ScheduledBehaviorRepository extends BehaviorRepository
{
    /**
     * @return array
     */
    public function findAllNotFinished(): array
    {
        $qb = $this->createQueryBuilder('sb');
        $qb->where('sb.finished = false');

        return $qb->getQuery()->getResult();
    }
}