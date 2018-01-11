<?php
declare(strict_types=1);
namespace App\Util\ConditionChecker\Factory;

use App\Util\ConditionChecker\Abstraction\AbstractConditionValueObject;
use App\Util\ConditionChecker\ValueObject\ConditionValueObject;
use App\Util\ConditionChecker\ValueObject\ConditionWithArgumentValueObject;

class ConditionValueObjectFactory
{
    const CONDITION_WITH_ARGUMENT_COUNT = 2;

    /**
     * @param array  $data
     * @param string $argument
     *
     * @return AbstractConditionValueObject
     */
    public function createCondition(array $data, string $argument = '')
    {
        if (self::CONDITION_WITH_ARGUMENT_COUNT === count($data)) {
            return $this->createWithArgument($data, $argument);
        }

        return $this->create($data);
    }

    /**
     * @param array $data
     *
     * @return AbstractConditionValueObject
     */
    private function create(array $data): AbstractConditionValueObject
    {
        return new ConditionValueObject($data);
    }

    /**
     * @param array  $data
     * @param string $argument
     *
     * @return AbstractConditionValueObject
     */
    private function createWithArgument(array $data, string $argument): AbstractConditionValueObject
    {
        return new ConditionWithArgumentValueObject($data, $argument);
    }
}