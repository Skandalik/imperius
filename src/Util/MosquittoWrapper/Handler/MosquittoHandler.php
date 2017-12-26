<?php
declare(strict_types=1);
namespace App\Util\MosquittoWrapper\Handler;

use App\Util\MosquittoWrapper\Factory\MosquittoFactory;
use Mosquitto\Client;

class MosquittoHandler
{
    /** @var string */
    private $mosquittoBroker;

    /** @var MosquittoFactory */
    private $mosquittoFactory;

    /** @var Client */
    private $mosquittoClient;

    public function __construct(string $mosquittoBroker, MosquittoFactory $mosquittoFactory)
    {
        $this->mosquittoBroker = $mosquittoBroker;
        $this->mosquittoFactory = $mosquittoFactory;
        $this->mosquittoClient = $this->mosquittoFactory->create();
    }

    public function connect(string $host = '')
    {
        if (empty($host)) {
            $this->mosquittoClient->connect($this->mosquittoBroker);

            return;
        }

        $this->mosquittoClient->connect($host);

        return;
    }

    public function disconnect()
    {
        $this->mosquittoClient->disconnect();
        unset($this->mosquittoClient);
    }

    public function getClient()
    {
        return $this->mosquittoClient;
    }

}