<?php
declare(strict_types=1);
namespace App\Entity\Behavior\Abstraction;

use App\Entity\Sensor;

interface BehaviorInterface
{
    /**
     * @return Sensor
     */
    public function getActionSensor(): Sensor;

    /**
     * @param Sensor $sensor
     *
     * @return $this
     */
    public function setActionSensor(Sensor $sensor);

    /**
     * @return string
     */
    public function getAction(): string;

    /**
     * @param string $action
     *
     * @return $this
     */
    public function setAction(string $action);

    /**
     * @return int | null
     */
    public function getActionArgument();

    /**
     * @param int | null $argument
     *
     * @return $this
     */
    public function setActionArgument($argument);
}