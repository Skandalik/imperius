<?php
declare(strict_types=1);
namespace App\Command;

use App\Event\SensorFoundEvent;
use App\Util\MosquittoWrapper\Handler\MosquittoHandler;
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

    /** @var  MosquittoHandler */
    private $mosquittoHandler;

    /** @var bool */
    private $inLoop;

    /** @var int */
    private $resumeAt;

    protected function configure()
    {
        $this->setName('scan:sensors');
    }

    public function __construct(
        $name = null,
        MosquittoHandler $mosquittoHandler,
        EventDispatcherInterface $eventDispatcher
    ) {
        parent::__construct($name);
        $this->mosquittoHandler = $mosquittoHandler;
        $this->eventDispatcher = $eventDispatcher;
        $this->inLoop = true;
        $this->resumeAt = 0;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        pcntl_signal(SIGTERM, [$this, 'stop']);
        pcntl_signal(SIGINT, [$this, 'stop']);

        $this->mosquittoHandler->create();

        $output->writeln("Scanning...");

        $this->mosquittoHandler->getClient()->onMessage(
            function ($message) use ($output) {

                if($message->topic === 'exit') {
                    $this->inLoop = 0;
                }

                $output->writeln("Found sensor!");
                /** @var Message $message */
                $output->writeln($message->payload);
                $jsonMessage = json_decode($message->payload, true);

                $event = new SensorFoundEvent();
                    $event->setUuid($jsonMessage['uuid']);
                    $event->setIp($jsonMessage['ip']);
                    $event->setSwitchable($jsonMessage['switchable']);
                    $event->setStatus($jsonMessage['status']);

                $this->eventDispatcher->dispatch(SensorFoundEvent::NAME, $event);
            }
        );

        $this->mosquittoHandler->connect();

        $this->mosquittoHandler->getClient()->subscribe('register', 1);
        $this->mosquittoHandler->getClient()->subscribe('exit', 1);

        $this->mosquittoHandler->getClient()->loopForever();
    }


    /**
     * @return void
     */
    private function wait(): void
    {
        $secondsToWait = $this->resumeAt - time();

        if ($secondsToWait > 0) {
            sleep($secondsToWait);
        }
    }

    public function stop()
    {
        $this->inLoop = false;
    }
}