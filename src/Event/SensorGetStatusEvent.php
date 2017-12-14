<?php
declare(strict_types=1);
namespace App\Event;

use App\Event\Enum\SensorEventEnum;
use Symfony\Component\EventDispatcher\Event;

class SensorGetStatusEvent extends SensorStatusEvent
{
    /** @var string $uuid */
    protected $uuid;

    /** @var int $value */
    protected $value;

    /** @var bool */
    private $awaitConfirmation;

    public function __construct(string $uuid, int $value, bool $awaitConfirmation = true)
    {
        $this->uuid = $uuid;
        $this->value = $value;
        $this->awaitConfirmation = $awaitConfirmation;
    }

    /**
     * @return string
     */
    public function getUuid(): string
    {
        return $this->uuid;
    }

    /**
     * @param string $uuid
     */
    public function setUuid(string $uuid)
    {
        $this->uuid = $uuid;
    }

    /**
     * @return int
     */
    public function getValue(): int
    {
        return $this->value;
    }

    /**
     * @param int $value
     */
    public function setValue(int $value)
    {
        $this->value = $value;
    }

    /**
     * @return bool
     */
    public function isAwaitConfirmation(): bool
    {
        return $this->awaitConfirmation;
    }

    /**
     * @param bool $awaitConfirmation
     */
    public function setAwaitConfirmation(bool $awaitConfirmation)
    {
        $this->awaitConfirmation = $awaitConfirmation;
    }
}