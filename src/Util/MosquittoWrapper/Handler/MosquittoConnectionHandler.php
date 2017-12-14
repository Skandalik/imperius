<?php
declare(strict_types=1);
namespace App\Util\MosquittoWrapper\Handler;

use Mosquitto\Client;

class MosquittoConnectionHandler
{
    /**
     * @var string
     */
    private $mosquittoBroker;

    public function __construct(string $mosquittoBroker)
    {
        $this->mosquittoBroker = $mosquittoBroker;
    }

    public function connect(Client $client)
    {
        $client->connect($this->mosquittoBroker);
    }

    public function disconnect(Client $client)
    {
        $client->disconnect();
    }

}