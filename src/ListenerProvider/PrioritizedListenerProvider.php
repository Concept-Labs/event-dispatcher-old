<?php
declare(strict_types=1);
namespace Cl\EventDispatcher\ListenerProvider\Aggregate;

use Cl\Container\ContainerInterface;
use Cl\Container\Iterator\Prioritized\PrioritizedContainer;
use Cl\Container\Iterator\Prioritized\PrioritizedContainerInterface;
use Cl\EventDispatcher\ListenerProviderAbstract;
use Psr\EventDispatcher\ListenerProviderInterface;


class PrioritizedListenerProvider extends ListenerProviderAbstract
{
    /**
     * @var PrioritizedContainerInterface Listeners container, organized by tag and priority
     */
    protected PrioritizedContainerInterface $listeners = null;

    /**
     * Constructor
     *
     * @param PrioritizedContainerInterface $listeners 
     */
    public function __construct(PrioritizedContainerInterface $listeners)
    {
        $this->listeners = $listeners ??
        //@TODO: DI
        new PrioritizedContainer();
    }
    /**
     * Retrieves all listeners for the given event from all registered providers.
     *
     * @param object $event The event for which to return the listeners.
     *
     * @return iterable An iterable of callables representing the event listeners.
     */
    public function getListenersForEvent(object $event): iterable
    {
        foreach ($this->listeners->get() as $listener) {
            yield $listener;
        }
    }

    /**
     * Adds a listener to the provider.
     *
     * @param callable $listener The listener to add.
     *
     * @return void
     */
    public function attachProvider($listener, int $priotity = 0): void
    {
        parent::attachListener($listener);
        $this->listeners->attach($listener, $priotity);
    }
    
    /**
     * Removes a listener from the provider.
     *
     * @param callable $listener The listener to remove.
     *
     * @return void
     */
    public function detachProvider($provider): void
    {
        //@TODO
        //parent::detachListener($provider);
    }

    /**
     * Counter
     *
     * @return int
     */
    public function count(): int
    {
        return $this->listeners->count();
    }
    
    /**
     * Reset
     *
     * @return void
     */
    public function reset(): void
    {
        $this->listeners->reset();
    }
}
