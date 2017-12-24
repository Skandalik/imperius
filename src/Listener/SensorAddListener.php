<?php
declare(strict_types=1);
namespace App\Listener;

use App\Event\SensorAddEvent;
use App\Util\MosquittoWrapper\MosquittoPublisher;
use Doctrine\ORM\EntityManagerInterface;

class SensorAddListener
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /** @var MosquittoPublisher */
    private $mosquittoPublisher;

    public function __construct(
        EntityManagerInterface $entityManager,
        MosquittoPublisher $mosquittoPublisher
    ) {
        $this->entityManager = $entityManager;
        $this->mosquittoPublisher = $mosquittoPublisher;
    }

    public function onSensorAdd(SensorAddEvent $event) {
        $this->entityManager->persist($event->getEntity());
        $this->entityManager->flush();

        if ($event->isFromScan()) {
            $this->mosquittoPublisher->publish($event->getEntity()->getUuid() . '/registered', '', 1, false);
        }

        return;
    }

}