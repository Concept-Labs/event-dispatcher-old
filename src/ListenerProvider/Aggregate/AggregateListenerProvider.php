<?php
declare(strict_types=1);
namespace Cl\EventDispatcher\ListenerProvider\Aggregate;



use Psr\EventDispatcher\ListenerProviderInterface;

/**
 * AggregateListenerProvider aggregates multiple ListenerProviderInterface instances.
 *
 * This class allows combining multiple listener providers into one, enabling a composite approach.
 *
 * @implements ListenerProviderInterface
 */
class AggregateListenerProvider implements ListenerProviderInterface, AggregateListenerProviderInterface
{
    /**
     * @var array<string, ListenerProviderInterface> $container
     *      Providers container, organized by tag and priority
     */
    protected array $providers = [];

    /**
     * Retrieves all listeners for the given event from all registered providers.
     *
     * @param object $event The event for which to return the listeners.
     *
     * @return iterable An iterable of callables representing the event listeners.
     */
    public function getListenersForEvent(object $event): iterable
    {
        foreach ($this->providers as $provider) {
            yield from $provider->getListenersForEvent($event);
        }
    }

    /**
     * Adds a listener provider to the aggregate provider.
     *
     * @param ListenerProviderInterface $provider The listener provider to add.
     *
     * @return void
     */
    public function attachProvider(ListenerProviderInterface $provider): void
    {
        $this->providers[] = $provider;
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
        $index = array_search($provider, $this->providers, true);
        if ($index !== false) {
            unset($this->providers[$index]);
        }
    }

    /**
     * Counter
     *
     * @return int
     */
    public function count(): int
    {
        return count($this->providers);
    }
    
    /**
     * Reset
     *
     * @return void
     */
    public function reset(): void
    {
        $this->providers = [];
    }
}
