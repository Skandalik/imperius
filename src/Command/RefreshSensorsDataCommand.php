<?php
declare(strict_types=1);
namespace App\Command;

use App\Entity\Job;
use App\Event\JobInterruptEvent;
use App\Repository\ScheduledBehaviorRepository;
use App\Util\SensorManager\SensorMosquittoPublisher;
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

class RefreshSensorsDataCommand extends ContainerAwareCommand
{
    const SENSORS_REFRESH = 'sensors:refresh';

    /** @var EventDispatcherInterface */
    private $eventDispatcher;

    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var ScheduledBehaviorRepository */
    private $repository;

    /** @var SensorMosquittoPublisher */
    private $publisher;

    public function __construct(
        $name = null,
        SensorMosquittoPublisher $publisher,
        EntityManagerInterface $entityManager,
        EventDispatcherInterface $eventDispatcher
    ) {
        parent::__construct($name);
        $this->publisher = $publisher;
        $this->entityManager = $entityManager;
        $this->eventDispatcher = $eventDispatcher;
    }

    protected function configure()
    {
        $this->setName(self::SENSORS_REFRESH);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("Start intervaled scanning sensors");
        $this->repository = $this->entityManager->getRepository(Job::class);
        try {
            while (true) {
                $this->entityManager->flush();
                $this->entityManager->clear();

                $this->publisher->publishCheckAllSensorsStatus();

                /** @var Job $job */
                $job = $this->repository->findByCommandName(self::SENSORS_REFRESH);
                $data = json_decode($job->getAdditionalData());
                $output->writeln("Refreshing " . date_format(new DateTime(), "Y-m-d H:i:s"));
                sleep($data->interval);
            }
        } catch (Exception $exception) {
            $event = new JobInterruptEvent(self::SENSORS_REFRESH);
            $this->eventDispatcher->dispatch(JobInterruptEvent::NAME, $event);
        }
    }
}