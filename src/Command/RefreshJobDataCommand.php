<?php
declare(strict_types=1);
namespace App\Command;

use App\Entity\Job;
use App\Event\Enum\JobEventEnum;
use App\Event\JobCheckEvent;
use App\Event\JobInterruptEvent;
use App\Repository\ScheduledBehaviorRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use function date_format;
use function json_decode;
use function sleep;

class RefreshJobDataCommand extends ContainerAwareCommand
{
    const JOBS_REFRESH = 'jobs:refresh';

    /** @var EventDispatcherInterface */
    private $eventDispatcher;

    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var ScheduledBehaviorRepository */
    private $repository;

    public function __construct(
        $name = null,
        EntityManagerInterface $entityManager,
        EventDispatcherInterface $eventDispatcher
    ) {
        parent::__construct($name);
        $this->entityManager = $entityManager;
        $this->eventDispatcher = $eventDispatcher;
    }

    protected function configure()
    {
        $this->setName(self::JOBS_REFRESH);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("Start intervaled scanning jobs");
        $this->repository = $this->entityManager->getRepository(Job::class);
        try {
            while (true) {
                $this->entityManager->flush();
                $this->entityManager->clear();

                $concreteJob = $this->repository->findByCommandName(self::JOBS_REFRESH);
                $data = json_decode($concreteJob->getAdditionalData());
                $jobs = $this->repository->findAll();
                /** @var Job $job */
                foreach ($jobs as $job) {
                    $event = new JobCheckEvent($job);

                    $this->eventDispatcher->dispatch(JobEventEnum::JOB_CHECK, $event);
                }
                $output->writeln("Refreshing " . date_format(new DateTime(), "Y-m-d H:i:s"));
                sleep($data->interval);
            }
        } catch (Exception $exception) {
            $event = new JobInterruptEvent(self::JOBS_REFRESH);
            $this->eventDispatcher->dispatch(JobInterruptEvent::NAME, $event);
        }
    }
}