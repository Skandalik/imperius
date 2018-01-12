<?php
declare(strict_types=1);
namespace App\Util\ActionManager\Abstraction;

abstract class AbstractActionValueObject
{
    /** @var string */
    protected $property;

    /** @var string */
    protected $expression;

    /** @var string */
    protected $argument;

    /**
     * @return string
     */
    public function getProperty(): string
    {
        return $this->property;
    }

    /**
     * @return string
     */
    public function getExpression(): string
    {
        return $this->expression;
    }

    /**
     * @return string
     */
    public function getArgument(): string
    {
        return $this->argument;
    }
}