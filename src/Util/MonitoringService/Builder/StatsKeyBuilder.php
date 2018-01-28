<?php
declare(strict_types=1);
namespace App\Util\MonitoringService\Builder;

class StatsKeyBuilder
{
    /**
     * @param string $name
     * @param array  $tags
     *
     * @return string
     */
    public function buildStatKey(string $name, array $tags): string
    {
        return sprintf('%s,%s', $name, $this->buildTags($tags));
    }

    /**
     * @param array $tags
     *
     * @return string
     */
    private function buildTags(array $tags): string
    {
        $mergedTags = [];
        foreach ($tags as $tag => $key) {
            $mergedTags[] = sprintf('%s=%s', $tag, $key);
        }

        return implode(',', $mergedTags);
    }
}