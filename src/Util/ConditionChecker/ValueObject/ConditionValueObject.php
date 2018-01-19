<?php
declare(strict_types=1);
namespace App\Util\ConditionChecker\ValueObject;

use App\Util\ConditionChecker\Abstraction\AbstractConditionValueObject;

class ConditionValueObject extends AbstractConditionValueObject
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