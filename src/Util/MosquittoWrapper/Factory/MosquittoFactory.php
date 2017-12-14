<?php
declare(strict_types=1);
namespace App\Util\MosquittoWrapper\Factory;

use Mosquitto\Client;

class MosquittoFactory
{
    /**
     * @var string
     */
    private $mosquittoId;

    public function __construct(string $mosquittoId)
    {
        $this->mosquittoId = $mosquittoId;
    }

    public function create()
    {
        return new Client($this->mosquittoId);
    }
}