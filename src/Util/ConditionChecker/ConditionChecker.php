<?php
declare(strict_types=1);
namespace App\Util\ConditionChecker;

use App\Entity\Sensor;
use App\Type\SensorConditionsEnumType;
use App\Util\ConditionChecker\Abstraction\AbstractConditionValueObject;
use App\Util\ConditionChecker\Factory\ConditionValueObjectFactory;
use function count;
use function intval;
use function strval;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

class ConditionChecker
{
    /** @var ConditionValueObjectFactory */
    private $conditionDataObjectFactory;

    /** @var PropertyAccessorInterface */
    private $propertyAccessor;

    public function __construct(
        ConditionValueObjectFactory $conditionDataObjectFactory,
        PropertyAccessorInterface $propertyAccessor
    ) {
        $this->conditionDataObjectFactory = $conditionDataObjectFactory;
        $this->propertyAccessor = $propertyAccessor;
    }

    /**
     * @param Sensor $sensor
     * @param string $conditionData
     * @param string $argument
     *
     * @return bool
     */
    public function checkCondition(Sensor $sensor, string $conditionData, string $argument = ''): bool
    {
        $condition = $this->conditionDataObjectFactory->createCondition(
            explode(' ', SensorConditionsEnumType::getValue($conditionData)),
            $argument
        );

        return $this->checkStatementWithEval(
            $condition->getStatement(strval($this->getSensorDataFromProperty($sensor, $condition)))
        );
    }

    /**
     * @param Sensor                       $sensor
     * @param AbstractConditionValueObject $conditionDataObject
     *
     * @return mixed
     */
    private function getSensorDataFromProperty(Sensor $sensor, AbstractConditionValueObject $conditionDataObject)
    {
        return intval($this->propertyAccessor->getValue($sensor, $conditionDataObject->getProperty()));
    }

    /**
     * @param string $statement
     *
     * @return bool
     */
    private function checkStatementWithEval(string $statement): bool
    {
        return eval('return ' . $statement . ';');
    }
}