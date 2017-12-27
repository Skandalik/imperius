<?php
declare(strict_types=1);
namespace App\Listener;

use App\Event\SensorAddEvent;
use App\Util\MosquittoWrapper\MosquittoPublisher;
use App\Util\TopicGenerator\TopicGenerator;
use Doctrine\ORM\EntityManagerInterface;

class SensorAddListener
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /** @var MosquittoPublisher */
    private $mosquittoPublisher;

    /**
     * @var TopicGenerator
     */
    private $topicGenerator;

    public function __construct(
        EntityManagerInterface $entityManager,
        MosquittoPublisher $mosquittoPublisher,
        TopicGenerator $topicGenerator
    ) {
        $this->entityManager = $entityManager;
        $this->mosquittoPublisher = $mosquittoPublisher;
        $this->topicGenerator = $topicGenerator;
    }

    public function onSensorAdd(SensorAddEvent $event)
    {
        $this->entityManager->persist($event->getEntity());
        $this->entityManager->flush();

        if ($event->isFromScan()) {
            $topic = $this->topicGenerator->generate($event->getEntity()->getUuid(), ['registered']);
            $this->mosquittoPublisher->publish($topic);
        }

        return;
    }

}