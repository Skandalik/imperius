<?php
declare(strict_types=1);
namespace App\Command;

use App\Entity\ScheduledBehavior;
use App\Repository\ScheduledBehaviorRepository;
use App\Util\ScheduledBehavior\ScheduledBehaviorManager;
use Doctrine\ORM\EntityManagerInterface;
use function sleep;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Process\Process;
use function usleep;

class ScanScheduledTasksCommand extends ContainerAwareCommand
{

    /** @var EventDispatcherInterface */
    private $eventDispatcher;

    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var ScheduledBehaviorRepository */
    private $repository;

    /** @var ScheduledBehaviorManager */
    private $scheduleExecutor;

    /** @var bool  */
    private $inLoop = true;

    public function __construct(
        $name = null,
        EventDispatcherInterface $eventDispatcher,
        EntityManagerInterface $entityManager,
        ScheduledBehaviorManager $scheduleExecutor
    ) {
        parent::__construct($name);
        $this->eventDispatcher = $eventDispatcher;
        $this->entityManager = $entityManager;
        $this->scheduleExecutor = $scheduleExecutor;
    }

    public function stop()
    {
        $this->inLoop = false;
    }

    protected function configure()
    {
        $this->setName('sensors:scheduled');
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->repository = $this->entityManager->getRepository(ScheduledBehavior::class);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("Start scanning scheduled tasks");

        pcntl_signal(SIGTERM, [$this, 'stop']);
        pcntl_signal(SIGINT, [$this, 'stop']);


        $output->writeln("Fetching database");
        while (true) {
            pcntl_signal_dispatch();
            $schedulers = $this->repository->findAllNotFinished();
            $output->writeln("Number of schedulers: " . count($schedulers));
            /** @var ScheduledBehavior $scheduled */
            foreach ($schedulers as $scheduled) {
                $this->scheduleExecutor->execute($scheduled);
            }
            usleep(3000000);
        }

        $output->writeln("Exiting gracefully...");
        return 0;
    }
}