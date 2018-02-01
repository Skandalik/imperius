<?php
declare(strict_types=1);
namespace App\Event;

use App\Event\Enum\JobEventEnum;
use Exception;
use Symfony\Component\EventDispatcher\Event;

class JobInterruptEvent extends Event
{
    const NAME = JobEventEnum::JOB_INTERRUPT;

    /** @var string $commandName */
    protected $commandName;

    /** @var Exception */
    private $exception;

    /**
     * JobInterruptEvent constructor.
     *
     * @param string         $commandName
     * @param Exception|null $exception
     */
    public function __construct(string $commandName, Exception $exception = null)
    {
        $this->commandName = $commandName;
        $this->exception = $exception;
    }

    /**
     * @return string
     */
    public function getCommandName(): string
    {
        return $this->commandName;
    }

    /**
     * @return Exception | null
     */
    public function getException()
    {
        return $this->exception;
    }
}