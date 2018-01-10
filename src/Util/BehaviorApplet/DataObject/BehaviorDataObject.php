<?php
declare(strict_types=1);
namespace App\Util\BehaviorApplet\DataObject;

use function intval;

class BehaviorDataObject
{
    /** @var string */
    private $property;

    /** @var string */
    private $expression;

    /** @var string */
    private $argument;

    public function __construct(array $behavior)
    {
        $this->property = $behavior[0];
        $this->expression = $behavior[1];
        $this->argument = strval($behavior[2]);
    }

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