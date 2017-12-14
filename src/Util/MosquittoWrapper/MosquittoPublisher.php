<?php
declare(strict_types=1);
namespace App\Util\MosquittoWrapper;

use App\Util\MosquittoWrapper\Factory\MosquittoFactory;
use App\Util\MosquittoWrapper\Handler\MosquittoConnectionHandler;

class MosquittoPublisher
{
    /**
     * @var MosquittoFactory
     */
    private $mosquittoFactory;

    /**
     * @var MosquittoConnectionHandler
     */
    private $connectionHandler;

    /**
     * MosquittoPublisher constructor.
     *
     * @param MosquittoFactory           $mosquittoFactory
     * @param MosquittoConnectionHandler $connectionHandler
     */
    public function __construct(MosquittoFactory $mosquittoFactory, MosquittoConnectionHandler $connectionHandler)
    {
        $this->mosquittoFactory = $mosquittoFactory;
        $this->connectionHandler = $connectionHandler;
    }

    public function publish(string $topic, string $payload, int $qos, bool $retain)
    {
        $mosquitto = $this->mosquittoFactory->create();
        $this->connectionHandler->connect($mosquitto);

        $mosquitto->onConnect(function () use ($mosquitto, $topic, $payload, $qos, $retain) {
            $mosquitto->publish($topic, $payload, $qos, $retain);
            $this->connectionHandler->disconnect($mosquitto);
        });

        $mosquitto->loopForever();
    }
}