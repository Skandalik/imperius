<?php
declare(strict_types=1);
namespace App\Util\ActionManager\Factory;

use App\Util\ActionManager\Abstraction\AbstractActionValueObject;
use App\Util\ActionManager\ValueObject\ActionValueObject;
use App\Util\ActionManager\ValueObject\ActionWithArgumentValueObject;
use function strval;

class ActionValueObjectFactory
{
    const ACTION_WITH_ARGUMENT_COUNT = 2;

    /**
     * @param array  $data
     * @param int | null $argument
     *
     * @return AbstractActionValueObject
     */
    public function create(array $data, $argument = null)
    {
        if (self::ACTION_WITH_ARGUMENT_COUNT === count($data)) {
            return $this->createActionWithArgument($data, strval($argument));
        }

        return $this->createAction($data);
    }

    /**
     * @param array $data
     *
     * @return AbstractActionValueObject
     */
    private function createAction(array $data): AbstractActionValueObject
    {
        return new ActionValueObject($data);
    }

    /**
     * @param array  $data
     * @param string $argument
     *
     * @return AbstractActionValueObject
     */
    private function createActionWithArgument(array $data, string $argument): AbstractActionValueObject
    {
        return new ActionWithArgumentValueObject($data, $argument);
    }
}