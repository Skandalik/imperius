<?php
declare(strict_types=1);
namespace App\Listener;

use App\Entity\ManualBehavior;
use App\Entity\Sensor;
use App\Util\LogHelper\LogContextEnum;
use App\Util\MonitoringService\StatsManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Psr\Log\LoggerInterface;

class SensorDeleteListener
{
    /** @var StatsManager */
    private $stats;

    /** @var LoggerInterface */
    private $logger;

    /**
     * SensorDeleteListener constructor.
     *
     * @param StatsManager    $stats
     * @param LoggerInterface $logger
     */
    public function __construct(StatsManager $stats, LoggerInterface $logger)
    {
        $this->stats = $stats;
        $this->logger = $logger;
    }

    /**
     * @param LifecycleEventArgs $eventArgs
     */
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

        $this->stats->setStatName('sensor');
        $this->stats->event(['action' => 'delete', 'uuid' => $entity->getUuid(),]);

        $this->logger->info(
            sprintf('Deleted Sensor: %s with UUID: %s', $entity->getId(), $entity->getUuid()),
            [
                LogContextEnum::SENSOR_UUID => $entity->getUuid(),
            ]
        );

        return;
    }

}