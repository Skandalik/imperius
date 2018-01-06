<?php
declare(strict_types=1);
namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Traits\IdentityAutoTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(attributes={"normalization_context"={"groups"={"behavior"}}})
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
     * @ORM\Column(name="property", type="string", columnDefinition="ENUM('active', 'status')", nullable=false)
     * @Groups({"behavior"})
     */
    private $property;

    /**
     * @var string
     * @ORM\Column(name="behavior_condition", type="string", nullable=false)
     * @Groups({"behavior"})
     */
    private $behaviorCondition;

    /**
     * @var Sensor
     *
     * @ORM\ManyToOne(targetEntity="Sensor")
     * @ORM\JoinColumn(name="dependent_sensor_id", referencedColumnName="id", nullable=false)
     * @Groups({"behavior"})
     */
    private $dependentSensor;

    /**
     * @var string
     * @ORM\Column(type="string", columnDefinition="ENUM('active', 'status')", nullable=false)
     * @Groups({"behavior"})
     */
    private $dependentSensorProperty;

    /**
     * @var string
     * @ORM\Column(name="behavior_action", type="string", nullable=false)
     * @Groups({"behavior"})
     */
    private $behaviorAction;

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
     * @return string
     */
    public function getProperty(): string
    {
        return $this->property;
    }

    /**
     * @param string $property
     *
     * @return Behavior
     */
    public function setProperty(string $property): Behavior
    {
        $this->property = $property;

        return $this;
    }

    /**
     * @return string
     */
    public function getBehaviorCondition(): string
    {
        return $this->behaviorCondition;
    }

    /**
     * @param string $behaviorCondition
     *
     * @return Behavior
     */
    public function setBehaviorCondition(string $behaviorCondition): Behavior
    {
        $this->behaviorCondition = $behaviorCondition;

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
    public function getDependentSensorProperty(): string
    {
        return $this->dependentSensorProperty;
    }

    /**
     * @param string $dependentSensorProperty
     *
     * @return Behavior
     */
    public function setDependentSensorProperty(string $dependentSensorProperty): Behavior
    {
        $this->dependentSensorProperty = $dependentSensorProperty;

        return $this;
    }

    /**
     * @return string
     */
    public function getBehaviorAction(): string
    {
        return $this->behaviorAction;
    }

    /**
     * @param string $behaviorAction
     *
     * @return Behavior
     */
    public function setBehaviorAction(string $behaviorAction): Behavior
    {
        $this->behaviorAction = $behaviorAction;

        return $this;
    }
}