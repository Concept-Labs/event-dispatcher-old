<?php
declare(strict_types=1);
namespace Cl\EventDispatcher\Dispatcher;

use Cl\EventDispatcher\Event\EventInterface;
use Cl\EventDispatcher\EventDispatcher;

class EventTypedDispatcher extends EventDispatcher
{

    /**
     * Dispatches an event.
     *
     * @param EventInterface $event The event to dispatch.
     *
     * @return EventInterface The dispatched event.
     */
    public function dispatch(EventInterface $event): EventInterface
    {
        return parent::dispatch($event);
    }

    /**
     * {@inheritDoc}
     */
    protected function beforeDispatch(EventInterface $event): void
    {
        parent::beforeDispatch($event);
        $event->setContext(['eventDispatcher' => $this]);
    }
}