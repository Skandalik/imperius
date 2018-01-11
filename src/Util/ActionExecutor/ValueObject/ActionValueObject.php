<?php
declare(strict_types=1);
namespace App\Util\ActionExecutor\ValueObject;

use App\Util\ActionExecutor\Abstraction\AbstractActionValueObject;

class ActionValueObject extends AbstractActionValueObject
{
    /**
     * ConditionDataObject constructor.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->property = $data[0];
        $this->expression = $data[1];
        $this->argument = strval($data[2]);
    }
}