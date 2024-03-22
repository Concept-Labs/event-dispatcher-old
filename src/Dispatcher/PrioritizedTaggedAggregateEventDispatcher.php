<?php
declare(strict_types=1);
namespace Cl\EventDispatcher\Dispatcher;

use Cl\Container\Iterator\Prioritized\PrioritizedTaggedContainer;
use Cl\Container\Iterator\Prioritized\PrioritizedTaggedContainerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;

class PrioritizedTaggedAggregateEventDispatcher 
    implements 
        AggregateEventDispatcherInterface
{

    /**
     * @var PrioritizedTaggedContainerInterface Event dispatchers container
     */
    protected ?PrioritizedTaggedContainerInterface $dispatchers = null;


    /**
     * Constructor.
     *
     * @param PrioritizedTaggedContainerInterface|null $dispatchers Prioritized container for event dispatchers.
     */
    public function __construct(?PrioritizedTaggedContainerInterface $dispatchers = null)
    {
        $this->dispatchers = $dispatchers ?? 
            //@TODO: DI
            new PrioritizedTaggedContainer();
    }

    /**
     * Dispatches an event to all attached event dispatchers.
     *
     * @param object $event The event to dispatch.
     * @param array  $tags  The tags.
     */
    public function dispatch(object $event, array $tags = []): void
    {
        /** @var EventDispatcherInterface $dispatcher */
        foreach ($this->dispatchers->getMultiple($tags) as $dispatcher) {
            $dispatcher->dispatch($event);
        }
    }

    /**
     * Attaches an event dispatcher with a given priority.
     *
     * @param EventDispatcherInterface $dispatcher The event dispatcher to attach.
     * @param array                    $tags       The tags.
     * @param int                      $priority   The priority of the attached dispatcher.
     * 
     * @return void
     */
    public function attachDispatcher(EventDispatcherInterface $dispatcher, array $tags = [], int $priority = 0): string
    {
        return $this->dispatchers->attach($dispatcher, $tags, $priority);
    }

    /**
     * Detaches an event dispatcher.
     *
     * @param EventDispatcherInterface $dispatcher The hash of the event dispatcher to detach.
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