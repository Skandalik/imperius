<?php
declare(strict_types=1);
namespace App\Command;

use App\Event\SensorFoundEvent;
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

    protected function configure()
    {
        $this->setName('scan:sensors');
    }

    public function __construct(
        $name = null,
        EventDispatcherInterface $eventDispatcher
    ) {
        parent::__construct($name);
        $this->eventDispatcher = $eventDispatcher;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //TODO popraw tworzenie klienta
        $client = new Client();

        $output->writeln("Scanning...");
        $client->onMessage(
            function ($message) use ($output) {
                $output->writeln("Found sensor!");
                /** @var Message $message */
                $output->writeln($message->payload);
                $jsonMessage = json_decode($message->payload, true);

                $event = new SensorFoundEvent(
                    $jsonMessage['uuid'],
                    $jsonMessage['ip'],
                    $jsonMessage['switchable'],
                    $jsonMessage['status']
                );

                $this->eventDispatcher->dispatch(SensorFoundEvent::NAME, $event);
            }
        );

        $client->connect('192.168.65.1');
        $output->writeln('Subscribe to topic "register".');

        $client->subscribe(TopicEnum::SENSOR_REGISTER, 1);
        $client->subscribe('exit', 1);

        $client->loopForever();
        $output->writeln('Exiting gracefully...');
    }
}
