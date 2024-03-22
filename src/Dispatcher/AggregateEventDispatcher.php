<?php
declare(strict_types=1);

namespace Cl\EventDispatcher\Dispatcher;

use Psr\EventDispatcher\EventDispatcherInterface;

/**
 * Class AggregateEventDispatcher
 *
 * Implementation of the AggregateEventDispatcherInterface that aggregates multiple event dispatchers.
 */
class AggregateEventDispatcher implements AggregateEventDispatcherInterface
{
    /**
     * @var EventDispatcherInterface[] Event dispatchers container
     */
    protected array $dispatchers = [];


    /**
     * Dispatches an event to all attached event dispatchers.
     *
     * @param object $event The event to dispatch.
     */
    public function dispatch(object $event): void
    {
        /** @var EventDispatcherInterface $dispatcher */
        foreach ($this->dispatchers as $dispatcher) {
            $dispatcher->dispatch($event);
        }
    }

    /**
     * Attaches an event dispatcher to the aggregator.
     *
     * @param EventDispatcherInterface $dispatcher The event dispatcher to attach.
     */
    public function attachDispatcher(EventDispatcherInterface $dispatcher): void
    {
        $this->dispatchers[] = $dispatcher;
    }

    /**
     * Detaches an event dispatcher from the aggregator.
     *
     * @param EventDispatcherInterface $dispatcher The event dispatcher to detach.
     */
    public function detachDispatcher(EventDispatcherInterface $dispatcher): void
    {
        // Remove the dispatcher from the array
        $index = array_search($dispatcher, $this->dispatchers, true);
        if ($index !== false) {
            unset($this->dispatchers[$index]);
        }
    }
}
