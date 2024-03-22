<?php
declare(strict_types=1);
namespace Cl\EventDispatcher\Event;

trait StoppableEventTrait
{
    /**
     * The event propgatation stop flag
     *
     * @var boolean
     */
    protected $___stopped = false;

    /**
     * {@inheritDoc}
     */
    public function isPropagationStopped() : bool
    {
        return $this->___stopped
    }

    /**
     * {@inheritDoc}
     */
    public function setIsPropagationStopped(bool $isStopped = true): void
    {
        $this->___stopped = $isStopped;
    }
}