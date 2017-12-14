<?php
declare(strict_types=1);
namespace App\Command;

use App\Event\SensorFoundEvent;
use App\Util\MosquittoWrapper\Factory\MosquittoFactory;
use App\Util\MosquittoWrapper\Handler\MosquittoConnectionHandler;
use Mosquitto\Client;
use Mosquitto\Message;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ScanSensorsCommand extends ContainerAwareCommand
{
    /** @var  MosquittoFactory */
    private $mosquittoFactory;

    /** @var  EventDispatcher */
    private $eventDispatcher;

    /** @var  MosquittoConnectionHandler */
    private $connectionHandler;

    protected function configure()
    {
        $this->setName('scan:sensors');
    }

    public function __construct(
        $name = null,
        MosquittoFactory $mosquittoFactory,
        MosquittoConnectionHandler $connectionHandler,
        EventDispatcherInterface $eventDispatcher
    ) {
        parent::__construct($name);
        $this->mosquittoFactory = $mosquittoFactory;
        $this->connectionHandler = $connectionHandler;
        $this->eventDispatcher = $eventDispatcher;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $mosquitto = $this->mosquittoFactory->create();

        $mosquitto->onMessage(
            function ($message) use ($output) {
                /** @var Message $message */
                $output->writeln($message->payload);
                $jsonMessage = json_decode($message->payload);

                $event = new SensorFoundEvent(
                    $jsonMessage->uuid,
                    $jsonMessage->ip,
                    $jsonMessage->switchable,
                    $jsonMessage->value
                );

                $this->eventDispatcher->dispatch(SensorFoundEvent::NAME, $event);
            }
        );

        $this->connectionHandler->connect($mosquitto);

        $mosquitto->subscribe('register', 1);
        $mosquitto->loopForever();
    }

}