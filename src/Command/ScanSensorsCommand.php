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
    /** @var  EventDispatcher */
    private $eventDispatcher;

    /** @var SensorValueRangeFactory */
    private $sensorValueRangeFactory;

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
        $client = new Client();

        $output->writeln("Scanning...");

        /** @var Message $message */
        $client->onMessage(
            function ($message) use ($output) {
                $jsonMessage = json_decode($message->payload, true);
                switch ($jsonMessage['action']) {
                    case 'register':
                        $event = new SensorFoundEvent(
                            $jsonMessage['uuid'],
                            $jsonMessage['ip'],
                            $jsonMessage['switchable'],
                            $jsonMessage['adjustable'],
                            $jsonMessage['status'],
                            $this->getSensorValueRange($jsonMessage)
                        );

                        $name = SensorFoundEvent::NAME;
                        break;
                    case 'update':
                        $event = new SensorUpdateEvent(
                            $jsonMessage['uuid'],
                            strval($jsonMessage['status'])
                        );

                        $name = SensorUpdateEvent::NAME;
                        break;
                    case 'disconnect':
                        echo "last will";
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
            }
        );

        $client->connect('raspberry.local');

        $client->subscribe(TopicEnum::SENSOR_REGISTER, 1);
        $client->subscribe(TopicEnum::SENSOR_STATUS_RESPONSE, 1);
        $client->subscribe(TopicEnum::SENSOR_LAST_WILL, 1);
        $client->subscribe('exit', 1);

        $client->loopForever();
        $output->writeln('Exiting gracefully...');
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
