<?php
declare(strict_types=1);
namespace Cl\EventDispatcher\Dispatcher;

use Cl\Container\Iterator\Prioritized\PrioritizedContainer;
use Cl\Container\Iterator\Prioritized\PrioritizedContainerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;

class PrioritizedAggregateEventDispatcher 
    implements 
        EventDispatcherInterface,
        AggregateEventDispatcherInterface
{

    /**
     * @var PrioritizedContainerInterface Event dispatchers container
     */
    protected ?PrioritizedContainerInterface $dispatchers = null;


    /**
     * Constructor.
     *
     * @param PrioritizedContainerInterface|null $dispatchers Prioritized container for event dispatchers.
     */
    public function __construct(?PrioritizedContainerInterface $dispatchers = null)
    {
        $this->dispatchers = $dispatchers ?? 
            //@TODO: DI
            new PrioritizedContainer();
    }

    /**
     * Dispatches an event to all attached event dispatchers.
     *
     * @param object $event The event to dispatch.
     */
    public function dispatch(object $event): void
    {
        /** @var EventDispatcherInterface $dispatcher */
        foreach ($this->dispatchers->get() as $dispatcher) {
            $dispatcher->dispatch($event);
        }
    }

    /**
     * Attaches an event dispatcher with a given priority.
     *
     * @param EventDispatcherInterface $dispatcher The event dispatcher to attach.
     * @param int                      $priority   The priority of the attached dispatcher.
     * 
     * @return void
     */
    public function attachDispatcher(EventDispatcherInterface $dispatcher, int $priority = 0): int|string
    {
        return $this->dispatchers->attach($dispatcher, $priority);
    }

    /**
     * Detaches an event dispatcher.
     *
     * @param int|string $hash The hash of the event dispatcher to detach.
     */
    public function detachDispatcher(EventDispatcherInterface $dispatcher): bool
    {
        // if (method_exists($this->dispatchers, 'detach')) {
        //     // Interface PrioritizedContainerInterface does not contains mandatory method "detach"
        //     return $this->dispatchers->detach($hash);
        // }
        // return false;
        return false;
    }
    
}