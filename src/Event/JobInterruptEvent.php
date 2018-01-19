<?php
declare(strict_types=1);
namespace App\Event;

use App\Event\Enum\JobEventEnum;
use Symfony\Component\EventDispatcher\Event;

class JobInterruptEvent extends Event
{
    const NAME = JobEventEnum::JOB_INTERRUPT;

    /** @var string $commandName */
    protected $commandName;

    public function __construct(string $commandName)
    {
        $this->commandName = $commandName;
    }

    /**
     * @return string
     */
    public function getCommandName(): string
    {
        return $this->commandName;
    }
}