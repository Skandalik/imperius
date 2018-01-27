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
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use function usleep;

class ScanScheduledTasksCommand extends ContainerAwareCommand
{
    const SENSORS_SCHEDULED = 'sensors:scheduled';

    /** @var EventDispatcherInterface */
    private $eventDispatcher;

    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var ScheduledBehaviorRepository */
    private $repository;

    public function __construct(
        $name = null,
        EventDispatcherInterface $eventDispatcher,
        EntityManagerInterface $entityManager
    ) {
        parent::__construct($name);
        $this->eventDispatcher = $eventDispatcher;
        $this->entityManager = $entityManager;
    }

    protected function configure()
    {
        $this->setName(self::SENSORS_SCHEDULED);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("Start scanning scheduled tasks");

        $output->writeln("Fetching database");
        $this->repository = $this->entityManager->getRepository(ScheduledBehavior::class);
        try {
            while (true) {
                $this->entityManager->flush();
                $this->entityManager->clear();
                $schedulers = $this->repository->findAllNotFinished();
                $output->writeln("Number of schedulers: " . count($schedulers));
                /** @var ScheduledBehavior $scheduled */
                foreach ($schedulers as $scheduled) {
                    $output->writeln(
                        'Next run for ' . $scheduled->getSensor()->getUuid() . ': ' . date_format(
                            $scheduled->getNextRunAt(),
                            'Y-m-d H:i:s'
                        )
                    );
                    $event = new ScheduledTaskExecuteEvent($scheduled);
                    $this->eventDispatcher->dispatch(ScheduledTaskEventEnum::SCHEDULED_TASK_EXECUTE, $event);
                }
                usleep(5000000);
            }
        } catch (Exception $exception) {
            $event = new JobInterruptEvent(self::SENSORS_SCHEDULED);
            $this->eventDispatcher->dispatch(JobInterruptEvent::NAME, $event);
        }
    }
}