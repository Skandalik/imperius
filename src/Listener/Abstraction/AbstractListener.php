<?php
declare(strict_types=1);
namespace App\Listener\Abstraction;

use Redis;

abstract class AbstractListener
{
    /** @var Redis */
    private $redis;

    public function __construct(Redis $redis)
    {
        $this->redis = $redis;
    }

    /**
     * @return Redis
     */
    public function getRedis(): Redis
    {
        return $this->redis;
    }

}