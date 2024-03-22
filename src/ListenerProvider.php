<?php
declare(strict_types=1);
namespace Cl\EventDispatcher;

use Cl\EventDispatcher\ListenerProvider\Exception\InvalidArgumentException;

/**
 * Class ListenerProvider
 *
 * @package Cl\EventDispatcher
 */
class SimpleListenerProvider extends ListenerProviderAbstract
{
    /**
     * @var array<string, callable>  Listeners containr
     */
    protected array $listeners = [];
  
    /**
     * {@inheritDoc}
     */
    public function getListenersForEvent(object $event): iterable
    {
        foreach ($this->listeners[$event::class] as $listener) {
            yield $listener;
        }
    }

    /**
     * Attach a listener
     *
     * @param callable $listener The callable listener
     * @param string   $event    The event name
     * 
     * @return void
     */
    public function attachListener($listener, string $event = ''): void
    {
        parent::attachListener($listener, $event);
        $this->assertEvent($event);
        
        $this->listeners[$event][] = $listener;
    }

    /**
     * Assert the event is a valid argument
     *
     * @param string $event The event
     * 
     * @return void
     * @throws InvalidArgumentException
     */
    protected function assertEvent(string $event) : void
    {
        if (empty($event)) {
            throw new InvalidArgumentException('Event name is empty');
        }
    }

}