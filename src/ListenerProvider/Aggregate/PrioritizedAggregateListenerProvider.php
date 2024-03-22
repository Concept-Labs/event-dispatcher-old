<?php
declare(strict_types=1);
namespace Cl\EventDispatcher\ListenerProvider\Aggregate;

use Cl\Container\Iterator\Prioritized\PrioritizedContainer;
use Cl\Container\Iterator\Prioritized\PrioritizedContainerInterface;
use Psr\EventDispatcher\ListenerProviderInterface;

/**
 * This class allows combining multiple listener providers into one, enabling a composite approach.
 *
 * @implements ListenerProviderInterface
 */
class PrioritizedAggregateListenerProvider 
    implements 
        ListenerProviderInterface,
        AggregateListenerProviderInterface
{
    /**
     * @var PrioritizedContainerInterface Providers container, organized by tag and priority
     */
    protected PrioritizedContainerInterface $providers = null;

    public function __construct(PrioritizedContainerInterface $providers)
    {
        $this->providers = $providers ??
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
        foreach ($this->providers->get() as $eventListener) {
            yield from $eventListener->getListenersForEvent($event);
        }
    }

    /**
     * Adds a listener provider to the aggregate provider.
     *
     * @param ListenerProviderInterface $provider The listener provider to add.
     *
     * @return void
     */
    public function attachProvider(ListenerProviderInterface $provider, int $priotity = 0): void
    {
        $this->providers->attach($provider, $priotity);
    }
    
    /**
     * Removes a listener provider from the aggregate provider.
     *
     * @param ListenerProviderInterface $provider The listener provider to remove.
     *
     * @return void
     */
    public function detachProvider(ListenerProviderInterface $provider): void
    {
        //@TODO in container
        //$this->providers->detach()
    }

    /**
     * Counter
     *
     * @return int
     */
    public function count(): int
    {
        return $this->providers->count();
    }
    
    /**
     * Reset
     *
     * @return void
     */
    public function reset(): void
    {
        $this->providers->reset();
    }
}
