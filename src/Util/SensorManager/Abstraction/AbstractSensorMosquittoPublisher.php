<?php
declare(strict_types=1);
namespace App\Util\SensorManager\Abstraction;

use App\Util\MosquittoWrapper\Handler\MosquittoHandler;
use App\Util\MosquittoWrapper\MosquittoPublisher;
use App\Util\TopicGenerator\TopicGenerator;

abstract class AbstractSensorMosquittoPublisher extends MosquittoPublisher
{
    /** @var MosquittoHandler */
    private $mosquittoHandler;

    /** @var TopicGenerator */
    private $topicGenerator;

    public function __construct(MosquittoHandler $mosquittoHandler, TopicGenerator $topicGenerator)
    {
        parent::__construct($mosquittoHandler);

        $this->mosquittoHandler = $mosquittoHandler;
        $this->topicGenerator = $topicGenerator;
    }

    /**
     * @return MosquittoHandler
     */
    public function getMosquittoHandler(): MosquittoHandler
    {
        return $this->mosquittoHandler;
    }

    /**
     * @return TopicGenerator
     */
    public function getTopicGenerator(): TopicGenerator
    {
        return $this->topicGenerator;
    }

}