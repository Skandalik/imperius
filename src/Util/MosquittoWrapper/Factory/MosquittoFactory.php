<?php
declare(strict_types=1);
namespace App\Util\MosquittoWrapper\Factory;

use Mosquitto\Client;

class MosquittoFactory
{
    public function create(string $clientId = '')
    {
        if (empty($clientId)) {
            return new Client(null, true);
        }

        return new Client($clientId, true);
    }
}