<?php
declare(strict_types=1);
namespace App\Command;

use App\Command\Factory\SensorValueRangeFactory;
use App\Command\ValueObject\SensorValueRangeValueObject;
use App\Event\JobInterruptEvent;
use App\Event\SensorCheckEvent;
use App\Event\SensorDisconnectEvent;
use App\Event\SensorFoundEvent;
use App\Util\TopicGenerator\Enum\TopicEnum;
use Exception;
use Mosquitto\Client;
use Mosquitto\Message;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use function array_key_exists;
use function getenv;
use function key_exists;

class ScanSensorsCommand extends ContainerAwareCommand
{
    //const MQTT_BROKER = 'docker.for.mac.localhost';
    const MQTT_BROKER_PORT = 1883;
    const MQTT_BROKER_KEEP_ALIVE = 30;
    const CLIENT_ID = 'imperius-sensor-scan-command';
    const SENSORS_SCAN = 'sensors:scan';

    /** @var  EventDispatcher */
    private $eventDispatcher;

    /** @var SensorValueRangeFactory */
    private $sensorValueRangeFactory;

    /** @var LoggerInterface */
    private $logger;

    /** @var array */
    private $topics = [
        TopicEnum::SENSOR_REGISTER        => 1,
        TopicEnum::SENSOR_STATUS_RESPONSE => 1,
        TopicEnum::SENSOR_RESPONSE        => 1,
        TopicEnum::SENSOR_LAST_WILL       => 1,
    ];

    protected function configure()
    {
        $this->setName(self::SENSORS_SCAN);
    }

    public function __construct(
        $name = null,
        EventDispatcherInterface $eventDispatcher,
        SensorValueRangeFactory $sensorValueRangeFactory,
        LoggerInterface $logger
    ) {
        parent::__construct($name);
        $this->eventDispatcher = $eventDispatcher;
        $this->sensorValueRangeFactory = $sensorValueRangeFactory;
        $this->logger = $logger;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            //TODO popraw tworzenie klienta
            $client = new Client(self::CLIENT_ID);

            $client->onDisconnect(
                function ($rc) use ($output, $client) {
                    $this->logger->error(sprintf('MQTT client disconnected. Failure with code %s.', $rc));
                }
            );

            $client->onConnect(
                function () use ($output) {
                    $this->logger->info('Connected to MQTT broker.');
                }
            );

            /** @var Message $message */
            $client->onMessage(
                function ($message) use ($output, $client) {
                    $jsonMessage = json_decode($message->payload, true);
                    if (key_exists('status', $jsonMessage)) {
                        if (is_string($jsonMessage['status'])) {
                            $tempStatus = (float) $jsonMessage['status'];
                            $jsonMessage['status'] = (int) round($tempStatus);
                        }
                    }
                    switch ($jsonMessage['action']) {
                        case 'register':
                            $event = new SensorFoundEvent(
                                $jsonMessage['uuid'],
                                $jsonMessage['ip'],
                                (bool) $jsonMessage['fetchable'],
                                (bool) $jsonMessage['switchable'],
                                (bool) $jsonMessage['adjustable'],
                                $jsonMessage['status'],
                                $this->getType($jsonMessage),
                                $this->getSensorValueRange($jsonMessage)
                            );

                            $name = SensorFoundEvent::NAME;
                            break;
                        case 'update':
                            $event = new SensorCheckEvent(
                                $jsonMessage['uuid'],
                                strval($jsonMessage['status'])
                            );

                            $name = SensorCheckEvent::NAME;
                            break;
                        case 'disconnect':
                            $event = new SensorDisconnectEvent($jsonMessage['uuid']);

                            $name = SensorDisconnectEvent::NAME;
                            break;
                        default:
                            $client->disconnect();
                            break;
                    }
                    $this->eventDispatcher->dispatch($name, $event);
                }
            );

            $this->logger->info(
                sprintf(
                    'Connecting to an MQTT broker on port: %s, IP: %s, keep alive: ',
                    self::MQTT_BROKER_PORT,
                    getenv('MOSQUITTO_BROKER_HOST'),
                    self::MQTT_BROKER_KEEP_ALIVE
                )
            );

            $this->connectMqttClient($client);

            $topics = "";
            foreach ($this->topics as $topic => $qos) {
                $topics .= ("- " . $topic);
                $client->subscribe($topic, $qos);
            }

            $this->logger->info(sprintf('Subscribed to topics: %s', $topics));

            $client->loopForever();
        } catch (Exception $exception) {
            $events = [];
            $events[] = new JobInterruptEvent(self::SENSORS_SCAN, $exception);
            $events[] = new JobInterruptEvent(ScanScheduledTasksCommand::SENSORS_SCHEDULED);
            $events[] = new JobInterruptEvent(RefreshSensorsDataCommand::SENSORS_REFRESH);
            foreach ($events as $event) {
                $this->eventDispatcher->dispatch(JobInterruptEvent::NAME, $event);
            }
        }
    }

    /**
     * @param Client $client
     */
    protected function connectMqttClient($client): void
    {
        $client->connect(getenv('MOSQUITTO_BROKER_HOST'), self::MQTT_BROKER_PORT, self::MQTT_BROKER_KEEP_ALIVE);
    }

    /**
     * @param array $jsonMessage
     *
     * @return SensorValueRangeValueObject|null
     */
    private function getSensorValueRange(array $jsonMessage)
    {
        if ($jsonMessage['adjustable']) {
            return $this->sensorValueRangeFactory->create(
                $jsonMessage['minValue'],
                $jsonMessage['maxValue']
            );
        }

        return null;
    }

    /**
     * @param array $jsonMessage
     *
     * @return string
     */
    private function getType(array $jsonMessage)
    {
        if (array_key_exists('type', $jsonMessage)) {
            return $jsonMessage['type'];
        }

        return 'none';
    }
}
