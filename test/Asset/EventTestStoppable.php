<?php
namespace Cl\EventDispatcher\Test\Asset;

use Psr\EventDispatcher\StoppableEventInterface;

/**
 * @covers EventTestStoppable
 */
class EventTestStoppable extends EventTest implements StoppableEventInterface
{
    public bool $stopped = false;
    public function isPropagationStopped(): bool
    {
        return $this->stopped;
    }
    
}