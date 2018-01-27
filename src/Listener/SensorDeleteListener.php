<?php
declare(strict_types=1);
namespace App\Listener;

use App\Entity\ManualBehavior;
use App\Entity\Sensor;
use Doctrine\ORM\Event\LifecycleEventArgs;

class SensorDeleteListener
{
    public function preRemove(LifecycleEventArgs $eventArgs)
    {
        $entity = $eventArgs->getEntity();

        if (!$entity instanceof Sensor) {
            return;
        }
        $repo = $eventArgs->getEntityManager()->getRepository(ManualBehavior::class);
        $manualBehaviors = $repo->findAllByActionSensor($entity);
        /** @var ManualBehavior $manualBehavior */
        foreach ($manualBehaviors as $manualBehavior) {
            $manualBehavior->getSensor()->removeManualBehavior($manualBehavior);
        }

        return;
    }

}