<?php
declare(strict_types=1);
namespace App\Util\TopicGenerator;

use App\Util\TopicGenerator\Enum\TopicEnum;

class TopicGenerator
{
    /**
     * @param string $uuid
     * @param array  $topicPostfix
     *
     * @return string
     */
    public function generate(string $uuid, array $topicPostfix): string
    {
        $topic = $uuid;

        foreach ($topicPostfix as $singleTopic) {
            $topic .= '/';
            $topic .= $singleTopic;
        }

        return sprintf('%s/%s', TopicEnum::SENSOR_TOPIC_PREFIX, $topic);
    }
}