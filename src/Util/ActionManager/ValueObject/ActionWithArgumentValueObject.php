<?php
declare(strict_types=1);
namespace App\Util\ActionManager\ValueObject;

use App\Util\ActionManager\Abstraction\AbstractActionValueObject;

class ActionWithArgumentValueObject extends AbstractActionValueObject
{
    /**
     * ConditionWithArgumentDataObject constructor.
     *
     * @param array  $data
     * @param string $argument
     */
    public function __construct(array $data, string $argument)
    {
        $this->property = $data[0];
        $this->expression = $data[1];
        $this->argument = strval($argument);
    }
}