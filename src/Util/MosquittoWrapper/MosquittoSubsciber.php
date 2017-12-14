<?php
declare(strict_types=1);
namespace App\Util\MosquittoWrapper;

use App\Util\MosquittoWrapper\Factory\MosquittoFactory;

class MosquittoSubsciber
{
    /**
     * @var MosquittoFactory
     */
    private $mosquittoFactory;

    /**
     * MosquittoPublisher constructor.
     *
     * @param MosquittoFactory $mosquittoFactory
     */
    public function __construct(MosquittoFactory $mosquittoFactory)
    {
        $this->mosquittoFactory = $mosquittoFactory;
    }

    public function subscribe(string $topic, string $payload, int $qos, bool $retain)
    {
        $mosquitto = $this->mosquittoFactory->create();

        $mosquitto->onSubscribe(function () use ($mosquitto, $topic, $payload, $qos, $retain) {
            $mosquitto->publish($topic, $payload, $qos, $retain);
            $mosquitto->disconnect();
        });

        $mosquitto->subscribe($topic, $qos);

        $mosquitto->loopForever();
    }
}