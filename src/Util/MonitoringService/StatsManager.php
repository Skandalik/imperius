<?php
declare(strict_types=1);
namespace App\Util\MonitoringService;

use App\Util\MonitoringService\Builder\StatsKeyBuilder;
use Liuggio\StatsdClient\Service\StatsdService;

class StatsManager
{
    /** @var string */
    private $name;

    /** @var StatsdService */
    private $service;

    /** @var StatsKeyBuilder */
    private $builder;

    public function __construct(StatsdService $service, StatsKeyBuilder $builder)
    {
        $this->service = $service;
        $this->builder = $builder;
    }

    /**
     * @param array $tags
     * @param int   $stat
     */
    public function gauge(array $tags = [], int $stat)
    {
        $this->service->gauge($this->buildTags($tags), $stat)->flush();
    }

    /**
     * @param array $tags
     * @param int   $stat
     */
    public function timing(array $tags = [], int $stat)
    {
        $this->service->timing($this->buildTags($tags), $stat)->flush();
    }

    /**
     * @param array $tags
     */
    public function increment(array $tags = [])
    {
        $this->service->increment($this->buildTags($tags))->flush();
    }

    /**
     * @param array $tags
     */
    public function decrement(array $tags = [])
    {
        $this->service->increment($this->buildTags($tags))->flush();
    }

    /**
     * @param array $tags
     */
    public function event(array $tags = [])
    {
        $this->service->timing($this->buildTags($tags), 0)->flush();
    }

    /**
     * @param string $name
     */
    public function setStatName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @param array $tags
     *
     * @return string
     */
    private function buildTags(array $tags)
    {
        return $this->builder->buildStatKey($this->name, $tags);
    }
}