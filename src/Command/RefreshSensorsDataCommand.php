<?php
declare(strict_types=1);
namespace App\Command;

use App\Entity\Job;
use App\Entity\Sensor;
use App\Event\JobInterruptEvent;
use App\Event\SensorConnectEvent;
use App\Event\SensorDisconnectEvent;
use App\Repository\ScheduledBehaviorRepository;
use App\Repository\SensorRepository;
use App\Util\SensorManager\SensorMosquittoPublisher;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
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

    /** @var SensorRepository */
    private $sensorRepository;

    /** @var SensorMosquittoPublisher */
    private $publisher;

    /** @var LoggerInterface */
    private $logger;

    /**
     * RefreshSensorsDataCommand constructor.
     *
     * @param null                     $name
     * @param SensorMosquittoPublisher $publisher
     * @param EntityManagerInterface   $entityManager
     * @param EventDispatcherInterface $eventDispatcher
     * @param LoggerInterface          $logger
     */
    public function __construct(
        $name = null,
        SensorMosquittoPublisher $publisher,
        EntityManagerInterface $entityManager,
        EventDispatcherInterface $eventDispatcher,
        LoggerInterface $logger
    ) {
        parent::__construct($name);
        $this->publisher = $publisher;
        $this->entityManager = $entityManager;
        $this->eventDispatcher = $eventDispatcher;
        $this->logger = $logger;
    }

    protected function configure()
    {
        $this->setName(self::SENSORS_REFRESH);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->repository = $this->entityManager->getRepository(Job::class);
        $this->sensorRepository = $this->entityManager->getRepository(Sensor::class);
        $this->logger->info('Starting refreshing sensors data job');
        try {
            while (true) {
                $this->entityManager->flush();
                $this->entityManager->clear();

                $this->publisher->publishCheckAllSensorsStatus();

                /** @var Job $job */
                $job = $this->repository->findByCommandName(self::SENSORS_REFRESH);
                $data = json_decode($job->getAdditionalData());

                $sensors = $this->sensorRepository->findAll();
                if (!empty($sensors)) {
                    foreach ($sensors as $sensor) {
                        $now = new DateTime();
                        $diff = $now->diff($sensor->getLastDataSentAt());
                        if ($diff->i === 0 && $diff->h === 0) {
                            if ($diff->s <= ($data->interval + 5)) {
                                $event = new SensorConnectEvent($sensor->getUuid());
                                $this->eventDispatcher->dispatch(SensorConnectEvent::NAME, $event);
                                continue;
                            }
                        }
                        $output->writeln($sensor->getUuid());
                        $event = new SensorDisconnectEvent($sensor->getUuid());
                        $this->eventDispatcher->dispatch(SensorDisconnectEvent::NAME, $event);
                    }
                }
                sleep($data->interval);
            }
        } catch (Exception $exception) {
            $event = new JobInterruptEvent(self::SENSORS_REFRESH, $exception);
            $this->eventDispatcher->dispatch(JobInterruptEvent::NAME, $event);
        }
    }
}