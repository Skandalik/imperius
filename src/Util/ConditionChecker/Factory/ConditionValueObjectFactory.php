<?php
declare(strict_types=1);
namespace App\Util\ConditionChecker\Factory;

use App\Util\ConditionChecker\Abstraction\AbstractConditionValueObject;
use App\Util\ConditionChecker\ValueObject\ConditionValueObject;
use App\Util\ConditionChecker\ValueObject\ConditionWithArgumentValueObject;
use function strval;

class ConditionValueObjectFactory
{
    const CONDITION_WITH_ARGUMENT_COUNT = 2;

    /**
     * @param array  $data
     * @param int | null $argument
     *
     * @return AbstractConditionValueObject
     */
    public function create(array $data, $argument = null)
    {
        if (self::CONDITION_WITH_ARGUMENT_COUNT === count($data)) {
            return $this->createConditionWithArgument($data, strval($argument));
        }

        return $this->createCondition($data);
    }

    /**
     * @param array $data
     *
     * @return AbstractConditionValueObject
     */
    private function createCondition(array $data): AbstractConditionValueObject
    {
        return new ConditionValueObject($data);
    }

    /**
     * @param array  $data
     * @param string $argument
     *
     * @return AbstractConditionValueObject
     */
    private function createConditionWithArgument(array $data, string $argument): AbstractConditionValueObject
    {
        return new ConditionWithArgumentValueObject($data, $argument);
    }
}