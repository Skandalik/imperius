<?php
declare(strict_types=1);
namespace App\Command;

use App\Command\Factory\SensorValueRangeFactory;
use App\Command\ValueObject\SensorValueRangeValueObject;
use App\Event\SensorDisconnectEvent;
use App\Event\SensorFoundEvent;
use App\Event\SensorUpdateEvent;
use App\Util\TopicGenerator\Enum\TopicEnum;
use Mosquitto\Client;
use Mosquitto\Message;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ScanSensorsCommand extends ContainerAwareCommand
{
    const MQTT_BROKER = '192.168.1.102';
    const MQTT_BROKER_PORT = 1883;
    const MQTT_BROKER_KEEP_ALIVE = 30;
    const CLIENT_ID = 'imperius-sensor-scan-command';

    /** @var  EventDispatcher */
    private $eventDispatcher;

    /** @var SensorValueRangeFactory */
    private $sensorValueRangeFactory;

    /** @var array */
    private $topics = [
        TopicEnum::SENSOR_REGISTER        => 1,
        TopicEnum::SENSOR_STATUS_RESPONSE => 1,
        TopicEnum::SENSOR_RESPONSE        => 1,
        TopicEnum::SENSOR_LAST_WILL       => 1,
        'exit'                            => 1,
    ];

    protected function configure()
    {
        $this->setName('sensors:scan');
    }

    public function __construct(
        $name = null,
        EventDispatcherInterface $eventDispatcher,
        SensorValueRangeFactory $sensorValueRangeFactory
    ) {
        parent::__construct($name);
        $this->eventDispatcher = $eventDispatcher;
        $this->sensorValueRangeFactory = $sensorValueRangeFactory;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //TODO popraw tworzenie klienta
        $client = new Client(self::CLIENT_ID);

        $output->writeln("");
        $output->writeln("");
        $output->writeln("=================================");
        $output->writeln("Imperius Home Automation Project");
        $output->writeln("=================================");
        $output->writeln("");
        $output->writeln("Starting command to scan for nearby sensors in your house!");
        $output->writeln("It will listen for every change that your sensor will do.");
        $output->writeln("");
        $output->writeln("");

        $client->onDisconnect(
            function ($rc) use ($output, $client) {
                $output->writeln('Disconnected. Failure with code: ' . $rc);
                $output->writeln('Connecting again.');
                $this->connectMqttClient($client);
            }
        );

        $client->onConnect(
            function () use ($output) {
                $output->writeln('Connected to MQTT Broker.');
            }
        );

        /** @var Message $message */
        $client->onMessage(
            function ($message) use ($output) {
                $jsonMessage = json_decode($message->payload, true);
                if (is_string($jsonMessage['status'])) {
                    $tempStatus = (float) $jsonMessage['status'];
                    $jsonMessage['status'] = (int) round($tempStatus);
                }
                switch ($jsonMessage['action']) {
                    case 'register':
                        $output->writeln("");
                        $output->writeln("Found new sensor!");
                        $event = new SensorFoundEvent(
                            $jsonMessage['uuid'],
                            $jsonMessage['ip'],
                            (bool) $jsonMessage['fetchable'],
                            (bool) $jsonMessage['switchable'],
                            (bool) $jsonMessage['adjustable'],
                            $jsonMessage['status'],
                            $this->getSensorValueRange($jsonMessage)
                        );

                        $name = SensorFoundEvent::NAME;
                        break;
                    case 'update':
                        $output->writeln("Sensor Update Event: Set Status: " . $jsonMessage['status']);
                        $event = new SensorUpdateEvent(
                            $jsonMessage['uuid'],
                            strval($jsonMessage['status'])
                        );

                        $name = SensorUpdateEvent::NAME;
                        break;
                    case 'disconnect':
                        $output->writeln("Sensor has disconnected unexpedectly. Received last will message.");
                        $event = new SensorDisconnectEvent($jsonMessage['uuid']);

                        $name = SensorDisconnectEvent::NAME;
                        break;
                    default:
                        $event = new SensorUpdateEvent(
                            $jsonMessage['uuid'],
                            $jsonMessage['ble']
                        );
                        break;
                }
                $this->eventDispatcher->dispatch($name, $event);
                $output->writeln("");
                $output->writeln("");
            }
        );

        $output->writeln("Connecting to an MQTT Broker on port " . self::MQTT_BROKER_PORT . '.');
        $output->writeln("IP of MQTT Broker " . self::MQTT_BROKER . '.');
        $output->writeln("Keep Alive responding for this command client: " . self::MQTT_BROKER_KEEP_ALIVE . '.');
        $output->writeln("");

        $this->connectMqttClient($client);

        $output->writeln("Subscribing to topics:");

        foreach ($this->topics as $topic => $qos) {
            $output->writeln("- " . $topic);
            $client->subscribe($topic, $qos);
        }

        $client->loopForever();
        $output->writeln('Exiting gracefully...');
    }

    /**
     * @param Client $client
     */
    protected function connectMqttClient($client): void
    {
        $client->connect(self::MQTT_BROKER, self::MQTT_BROKER_PORT, self::MQTT_BROKER_KEEP_ALIVE);
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
}
