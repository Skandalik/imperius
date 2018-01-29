<?php
declare(strict_types=1);
namespace App\Util\MosquittoWrapper\Handler;

use App\Util\MosquittoWrapper\Factory\MosquittoFactory;
use Mosquitto\Client;
use function is_null;

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
        $this->mosquittoClient = null;
    }

    public function getClient()
    {
        if (is_null($this->mosquittoClient)) {
            $this->mosquittoClient = $this->mosquittoFactory->create();
        }

        return $this->mosquittoClient;
    }

}