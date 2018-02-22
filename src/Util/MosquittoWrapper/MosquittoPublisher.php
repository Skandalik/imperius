<?php
declare(strict_types=1);
namespace App\Util\MosquittoWrapper;

use App\Util\MosquittoWrapper\Handler\MosquittoHandler;

class MosquittoPublisher
{
    /** @var MosquittoHandler */
    private $mosquittoHandler;

    public function __construct(MosquittoHandler $mosquittoHandler)
    {
        $this->mosquittoHandler = $mosquittoHandler;
    }

    public function publish(string $topic, string $payload = '', int $qos = 1, bool $retain = false)
    {
        $this->mosquittoHandler->getClient()->onConnect(
            function () use ($topic, $payload, $qos, $retain) {
                $this->mosquittoHandler->getClient()->publish($topic, $payload, $qos, $retain);
            $this->mosquittoHandler->disconnect();
        });
        $this->mosquittoHandler->connect();
        $this->mosquittoHandler->getClient()->loopForever();
    }
}