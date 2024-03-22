<?php
declare(strict_types=1);
namespace Cl\EventDispatcher;


use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\ListenerProviderInterface;
use Psr\EventDispatcher\StoppableEventInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

use Cl\EventDispatcher\Dispatcher\Exception\EventPropagationIsStoppedException;

/**
 * EventDispatcher implementation.
 */
class EventDispatcher implements EventDispatcherInterface, LoggerAwareInterface
{

    use LoggerAwareTrait;    
    protected ListenerProviderInterface $eventListenerProvider;

    /**
     * @var callable $invoker
     */
    protected mixed $invoker;

    /**
     * Constructor.
     *
     * @param ListenerProviderInterface $eventListenerProvider
     *        The listener provider for events.
     * @param callable                  $invoker
     *        The invoker for invoking (optional, defaults to NullLogger).
     *        The invoker must receive two mandatory arguments (callable $callable, array $args)
     * @param LoggerInterface           $logger
     *        The Psr-16 Cache
     */
    public function __construct(
        ListenerProviderInterface $eventListenerProvider, 
        callable                  $invoker = null,
        ?LoggerInterface          $logger = null,
    ) {
        $this->eventListenerProvider = $eventListenerProvider;
        $this->invoker = $invoker ?? $this->getDefaultInvoker();
        $this->logger = $logger ?? new NullLogger();
    }

    /**
     * Dispatches an event.
     *
     * @param object $event The event to dispatch.
     *
     * @return object The dispatched event.
     */
    public function dispatch(object $event): object
    {
        
        /** @var callable $listener */
        foreach ($this->getListenersForEvent($event) as $listener) {
            try{
                
                $this->beforeDispatch($event);

                $this->invokeListener($listener, $event);

                $this->afterDispatch($event);

            } catch (EventPropagationIsStoppedException $e) {
                //stop process listeners
                return $event;
            } catch (\Throwable $e) {
                $this->logger->debug(
                    sprintf(_('Exception during listener call for event %s: %s'), $event::class, $e->getMessage())
                );
                throw $e;
            }
        }
        return $event;
    }

    /**
     * Do predispatch stuff
     *
     * @param object $event 
     * 
     * @return void
     */
    protected function beforeDispatch(object &$event): void
    {
        $this->assertEventPropagationIsStopped($event);
    }
    
    /**
     * Do after dispatch stuff
     *
     * @param object $event 
     * 
     * @return void
     */
    protected function afterDispatch(object &$event): void
    {
        $this->assertEventPropagationIsStopped($event);
    }

    /**
     * Invokes the listener for the event.
     *
     * @param callable $listener The listener to invoke.
     * @param object   $event    The event.
     *
     * @return mixed The result of the listener invocation.
     *
     * @throws \Throwable An invoker is able to throw its own exception
     * 
     */
    protected function invokeListener(callable|string $listener, object $event): mixed
    {
        $invoker = $this->getInvoker();

        // Wrap to detatch $this from dispatcher
        return (static function () use ($invoker, $listener, $event) {
            return $invoker($listener, $event);
        })();
        //return $listener($event); //simple invocation
    }

    /**
     * Gets the invoker.
     *
     * @return callable
     */
    protected function getInvoker(): callable
    {
        return $this->invoker;
    }



    /**
     * Assert event propagation
     *
     * @param object $event The event
     * 
     * @return void
     * @throws EventPropagationIsStoppedException If the event propagation is stopped
     */
    protected function assertEventPropagationIsStopped(object $event): void
    {
        if ($event instanceof StoppableEventInterface && $event->isPropagationStopped()) {
            throw new EventPropagationIsStoppedException(_('Event stopped'));
        }
    }

    /**
     * Gets the listener provider.
     *
     * @return ListenerProviderInterface The listener provider.
     */
    protected function getEventListenerProvider(): ListenerProviderInterface
    {
        return $this->eventListenerProvider;
    }

    /**
     * Gets the listeners for the event.
     *
     * @param object $event The event.
     *
     * @return iterable The iterable of listeners.
     */
    protected function getListenersForEvent(object $event): iterable
    {
        yield from $this->getEventListenerProvider()->getListenersForEvent($event);
    }


    /**
     * The default invoker
     *
     * @return callable
     */
    protected function getDefaultInvoker(): callable
    {
        return static function (callable $callable, $event) {
            return call_user_func_array($callable, [$event]);
        };
        
    }

}