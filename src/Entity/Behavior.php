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
     * @ORM\Column(name="source_property", type="string", columnDefinition="ENUM('active', 'status')", nullable=false)
     * @Groups({"behavior"})
     */
    private $sourceProperty;

    /**
     * @var string
     *
     * @ORM\Column(name="predicate", type="string", nullable=false)
     * @Groups({"behavior"})
     */
    private $predicate;

    /**
     * @var string
     *
     * @ORM\Column(name="predicate_argument", type="string", nullable=false)
     * @Groups({"behavior"})
     */
    private $predicateArgument;

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
     * @ORM\Column(name="dependent_property", type="string", columnDefinition="ENUM('active', 'status')", nullable=false)
     * @Groups({"behavior"})
     */
    private $dependentProperty;
    /**
     * @var string
     *
     * @ORM\Column(name="action", type="string", nullable=false)
     * @Groups({"behavior"})
     */
    private $action;

    /**
     * @var string
     *
     * @ORM\Column(name="action_argument", type="string", nullable=false)
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
     * @return string
     */
    public function getSourceProperty(): string
    {
        return $this->sourceProperty;
    }

    /**
     * @param string $sourceProperty
     *
     * @return Behavior
     */
    public function setSourceProperty(string $sourceProperty): Behavior
    {
        $this->sourceProperty = $sourceProperty;

        return $this;
    }

    /**
     * @return string
     */
    public function getPredicate(): string
    {
        return $this->predicate;
    }

    /**
     * @param string $predicate
     *
     * @return Behavior
     */
    public function setPredicate(string $predicate): Behavior
    {
        $this->predicate = $predicate;

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
    public function getDependentProperty(): string
    {
        return $this->dependentProperty;
    }

    /**
     * @param string $dependentProperty
     *
     * @return Behavior
     */
    public function setDependentProperty(string $dependentProperty): Behavior
    {
        $this->dependentProperty = $dependentProperty;

        return $this;
    }

    /**
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * @param string $action
     *
     * @return Behavior
     */
    public function setAction(string $action): Behavior
    {
        $this->action = $action;

        return $this;
    }

    /**
     * @return string
     */
    public function getPredicateArgument(): string
    {
        return $this->predicateArgument;
    }

    /**
     * @param string $predicateArgument
     */
    public function setPredicateArgument(string $predicateArgument)
    {
        $this->predicateArgument = $predicateArgument;
    }

    /**
     * @return string
     */
    public function getActionArgument(): string
    {
        return $this->actionArgument;
    }

    /**
     * @param string $actionArgument
     */
    public function setActionArgument(string $actionArgument)
    {
        $this->actionArgument = $actionArgument;
    }
}