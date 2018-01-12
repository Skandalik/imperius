<?php
declare(strict_types=1);
namespace App\Util\ActionExecutor\Factory;

use App\Util\ActionExecutor\Abstraction\AbstractActionValueObject;
use App\Util\ActionExecutor\ValueObject\ActionValueObject;
use App\Util\ActionExecutor\ValueObject\ActionWithArgumentValueObject;

class ActionValueObjectFactory
{
    const CONDITION_WITH_ARGUMENT_COUNT = 2;

    /**
     * @param array  $data
     * @param string $argument
     *
     * @return AbstractActionValueObject
     */
    public function createAction(array $data, string $argument = '')
    {
        if (self::CONDITION_WITH_ARGUMENT_COUNT === count($data)) {
            return $this->createWithArgument($data, $argument);
        }

        return $this->create($data);
    }

    /**
     * @param array $data
     *
     * @return AbstractActionValueObject
     */
    private function create(array $data): AbstractActionValueObject
    {
        return new ActionValueObject($data);
    }

    /**
     * @param array  $data
     * @param string $argument
     *
     * @return AbstractActionValueObject
     */
    private function createWithArgument(array $data, string $argument): AbstractActionValueObject
    {
        return new ActionWithArgumentValueObject($data, $argument);
    }
}