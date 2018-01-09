<?php
declare(strict_types=1);
namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Traits\IdentityAutoTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     attributes={
 *      "normalization_context"={"groups"={"behavior"}},
 *      "force_eager"=false
 *     })
 * @ORM\Entity(repositoryClass="App\Repository\BehaviorRepository")
 * @ORM\Table(name="imp_behavior")
 */
class Behavior
{
    use IdentityAutoTrait;

    /**
     * @var Sensor
     *
     * @ORM\ManyToOne(targetEntity="Sensor", inversedBy="behaviors")
     * @ORM\JoinColumn(name="source_sensor_id", referencedColumnName="id", nullable=false)
     * @Groups({"behavior"})
     */
    private $sourceSensor;

    /**
     * @var string
     *
     * @ORM\Column(name="source_condition", type="sensor_conditions_enum", nullable=false)
     * @Groups({"behavior"})
     */
    private $sourceCondition;

    /**
     * @var int
     *
     * @ORM\Column(name="source_argument", type="integer", nullable=true)
     * @Groups({"behavior"})
     */
    private $sourceArgument;

    /**
     * @var Sensor
     *
     * @ORM\ManyToOne(targetEntity="Sensor", cascade={"persist"})
     * @ORM\JoinColumn(name="dependent_sensor_id", referencedColumnName="id", nullable=false)
     * @Groups({"behavior"})
     */
    private $dependentSensor;

    /**
     * @var string
     *
     * @ORM\Column(name="dependent_action", type="sensor_actions_enum", nullable=false)
     * @Groups({"behavior"})
     */
    private $dependentAction;

    /**
     * @var int
     *
     * @ORM\Column(name="action_argument", type="integer", nullable=true)
     * @Groups({"behavior"})
     */
    private $actionArgument;

    /**
     * @return Sensor
     */
    public function getSourceSensor(): Sensor
    {
        return $this->sourceSensor;
    }

    /**
     * @param $sourceSensor
     *
     * @return Behavior
     */
    public function setSourceSensor($sourceSensor): Behavior
    {
        $this->sourceSensor = $sourceSensor;

        return $this;
    }

    /**
     * @return $this
     */
    public function removeSourceSensor()
    {
        $this->setSourceSensor(new Sensor());

        return $this;
    }

    /**
     * @return Sensor
     */
    public function getDependentSensor(): Sensor
    {
        return $this->dependentSensor;
    }

    /**
     * @param Sensor $dependentSensor
     *
     * @return Behavior
     */
    public function setDependentSensor(Sensor $dependentSensor): Behavior
    {
        $this->dependentSensor = $dependentSensor;

        return $this;
    }

    /**
     * @return string
     */
    public function getSourceCondition(): string
    {
        return $this->sourceCondition;
    }

    /**
     * @param string $sourceCondition
     *
     * @return Behavior
     */
    public function setSourceCondition(string $sourceCondition)
    {
        $this->sourceCondition = $sourceCondition;

        return $this;
    }

    /**
     * @return int
     */
    public function getSourceArgument(): int
    {
        return $this->sourceArgument;
    }

    /**
     * @param int | null $sourceArgument
     *
     * @return Behavior
     */
    public function setSourceArgument($sourceArgument)
    {
        $this->sourceArgument = $sourceArgument;

        return $this;
    }

    /**
     * @return string
     */
    public function getDependentAction(): string
    {
        return $this->dependentAction;
    }

    /**
     * @param string $dependentAction
     *
     * @return Behavior
     */
    public function setDependentAction(string $dependentAction)
    {
        $this->dependentAction = $dependentAction;

        return $this;
    }

    /**
     * @return int
     */
    public function getActionArgument(): int
    {
        return $this->actionArgument;
    }

    /**
     * @param int | null $actionArgument
     *
     * @return Behavior
     */
    public function setActionArgument($actionArgument)
    {
        $this->actionArgument = $actionArgument;

        return $this;
    }


}