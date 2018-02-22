<?php
declare(strict_types=1);
namespace App\Util\ConditionChecker;

use App\Entity\ManualBehavior;
use App\Entity\Sensor;
use App\Type\SensorConditionsEnumType;
use App\Util\ConditionChecker\Abstraction\AbstractConditionValueObject;
use App\Util\ConditionChecker\Factory\ConditionValueObjectFactory;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use function strval;

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
     * @param ManualBehavior $behavior
     *
     * @return bool
     */
    public function checkCondition(ManualBehavior $behavior): bool
    {
        $map = SensorConditionsEnumType::getReadableValues();

        $condition = $this->conditionDataObjectFactory->create(
            explode(' ', SensorConditionsEnumType::getValue($behavior->getRequirement())),
            strval($behavior->getRequirementArgument())
        );

        return $this->checkStatementWithEval(
            $condition->getStatement(($this->getDataFromProperty($behavior->getSensor(), $condition)))
        );
    }

    /**
     * @param Sensor                       $sensor
     * @param AbstractConditionValueObject $conditionDataObject
     *
     * @return mixed
     */
    private function getDataFromProperty(Sensor $sensor, AbstractConditionValueObject $conditionDataObject)
    {
        return strval((int) $this->propertyAccessor->getValue($sensor, $conditionDataObject->getProperty()));
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