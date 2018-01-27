<?php
declare(strict_types=1);
namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Behavior\Abstraction\BehaviorInterface;
use App\Entity\Traits\IdentityAutoTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     attributes={
 *      "normalization_context"={"groups"={"manual", "common"}},
 *      "denormalization_context"={"groups"={"manual", "common"}},
 *      "force_eager"=false
 *     })
 * @ORM\Entity(repositoryClass="App\Repository\ManualBehaviorRepository")
 * @ORM\Table(name="imp_manual_behavior")
 * @ORM\HasLifecycleCallbacks()
 */
class ManualBehavior implements BehaviorInterface
{
    use IdentityAutoTrait;

    /**
     * @var Sensor
     *
     * @ORM\ManyToOne(targetEntity="Sensor", inversedBy="manualBehaviors")
     * @ORM\JoinColumn(name="sensor_id", referencedColumnName="id", nullable=false)
     * @Groups({"manual"})
     */
    private $sensor;

    /**
     * @var string
     *
     * @ORM\Column(name="requirement", type="sensor_conditions_enum", nullable=false)
     * @Groups({"manual"})
     */
    private $requirement;

    /**
     * @var int
     *
     * @ORM\Column(name="requirement_argument", type="integer", nullable=true)
     * @Groups({"manual"})
     */
    private $requirementArgument;

    /**
     * @var Sensor
     *
     * @ORM\ManyToOne(targetEntity="Sensor")
     * @ORM\JoinColumn(name="action_sensor_id", referencedColumnName="id", nullable=false)
     * @Groups({"manual"})
     */
    private $actionSensor;

    /**
     * @var string
     *
     * @ORM\Column(type="sensor_actions_enum", nullable=false)
     * @Groups({"manual"})
     */
    private $action;

    /**
     * @var int
     *
     * @ORM\Column(name="action_argument", type="integer", nullable=true)
     * @Groups({"manual"})
     */
    private $actionArgument;

    /**
     * @return Sensor
     */
    public function getSensor(): Sensor
    {
        return $this->sensor;
    }

    /**
     * @param Sensor $sensor
     *
     * @return ManualBehavior
     */
    public function setSensor(Sensor $sensor = null)
    {
        $this->sensor = $sensor;

        return $this;
    }

    /**
     * @return $this
     */
    public function removeSourceSensor()
    {
        $this->setSensor(new Sensor());

        return $this;
    }

    /**
     * @return $this
     */
    public function removeActionSensor()
    {
        $this->setActionSensor(new Sensor());

        return $this;
    }

    /**
     * @return Sensor
     */
    public function getActionSensor(): Sensor
    {
        return $this->actionSensor;
    }

    /**
     * @param Sensor $actionSensor
     *
     * @return $this
     */
    public function setActionSensor(Sensor $actionSensor)
    {
        $this->actionSensor = $actionSensor;

        return $this;
    }

    /**
     * @return string
     */
    public function getRequirement(): string
    {
        return $this->requirement;
    }

    /**
     * @param string $requirement
     *
     * @return ManualBehavior
     */
    public function setRequirement(string $requirement)
    {
        $this->requirement = $requirement;

        return $this;
    }

    /**
     * @return null | int
     */
    public function getRequirementArgument()
    {
        return $this->requirementArgument;
    }

    /**
     * @param int | null $requirementArgument
     *
     * @return ManualBehavior
     */
    public function setRequirementArgument($requirementArgument)
    {
        $this->requirementArgument = $requirementArgument;

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
     * @return $this
     */
    public function setAction(string $action)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * @return int | null
     */
    public function getActionArgument()
    {
        return $this->actionArgument;
    }

    /**
     * @param int | null $argument
     *
     * @return $this
     */
    public function setActionArgument($argument)
    {
        $this->actionArgument = $argument;

        return $this;
    }
}
