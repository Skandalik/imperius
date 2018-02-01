<?php
declare(strict_types=1);
namespace App\Command;

use App\Entity\ScheduledBehavior;
use App\Event\Enum\ScheduledTaskEventEnum;
use App\Event\JobInterruptEvent;
use App\Event\ScheduledTaskExecuteEvent;
use App\Repository\ScheduledBehaviorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use function sleep;

class ScanScheduledTasksCommand extends ContainerAwareCommand
{
    const SENSORS_SCHEDULED = 'sensors:scheduled';

    /** @var EventDispatcherInterface */
    private $eventDispatcher;

    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var ScheduledBehaviorRepository */
    private $repository;

    /** @var LoggerInterface */
    private $logger;

    /**
     * ScanScheduledTasksCommand constructor.
     *
     * @param null                     $name
     * @param EventDispatcherInterface $eventDispatcher
     * @param EntityManagerInterface   $entityManager
     * @param LoggerInterface          $logger
     */
    public function __construct(
        $name = null,
        EventDispatcherInterface $eventDispatcher,
        EntityManagerInterface $entityManager,
        LoggerInterface $logger
    ) {
        parent::__construct($name);
        $this->eventDispatcher = $eventDispatcher;
        $this->entityManager = $entityManager;
        $this->logger = $logger;
    }

    protected function configure()
    {
        $this->setName(self::SENSORS_SCHEDULED);
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->repository = $this->entityManager->getRepository(ScheduledBehavior::class);
        $this->logger->info('Starting scanning scheduled tasks');
        try {
            while (true) {
                $this->entityManager->flush();
                $this->entityManager->clear();
                $schedulers = $this->repository->findAllNotFinished();
                /** @var ScheduledBehavior $scheduled */
                foreach ($schedulers as $scheduled) {
                    $event = new ScheduledTaskExecuteEvent($scheduled);
                    $this->eventDispatcher->dispatch(ScheduledTaskEventEnum::SCHEDULED_TASK_EXECUTE, $event);
                }
                sleep(30);
            }
        } catch (Exception $exception) {
            $event = new JobInterruptEvent(self::SENSORS_SCHEDULED, $exception);
            $this->eventDispatcher->dispatch(JobInterruptEvent::NAME, $event);
        }
    }
}