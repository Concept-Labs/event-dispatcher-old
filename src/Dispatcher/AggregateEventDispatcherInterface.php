<?php
declare(strict_types=1);
namespace Cl\EventDispatcher\Dispatcher;
use Psr\EventDispatcher\EventDispatcherInterface;

interface AggregateEventDispatcherInterface extends EventDispatcherInterface
{

    
    public function attachDispatcher(EventDispatcherInterface $dispatcher);
    public function detachDispatcher(EventDispatcherInterface $dispatcher);
}