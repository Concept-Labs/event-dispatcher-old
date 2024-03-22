<?php
declare(strict_types=1);

namespace Cl\Tests\EventDispatcher;

use Cl\Able\Disableable\DisableableInterface;
use Cl\Able\Disableable\DisableableTrait;
use Cl\Able\Resettable\ResettableInterface;
use Cl\EventDispatcher\Event\EventInterface;
use Cl\EventDispatcher\Event\StoppableEventInterface;
use Cl\EventDispatcher\Event\TaggableEventInterface;
use Cl\EventDispatcher\ListenerProvider\TaggedPriorityListenerProvider;
use PHPUnit\Framework\TestCase;


/**
 * @covers Cl\EventDispatcher\ListenerProvider\TaggedPriorityListenerProvider
 */
class TaggedPriorityListenerProviderTest extends TestCase
{
    public function testProvide(): void
    {
     
        $provider = new TaggedPriorityListenerProvider();
        
        $listener1 = static fn ($event) => $event->add(' TH');
        $listener2 = static fn ($event) => $event->add('HI');
        $listener3 = static fn ($event) =>$event->add('ERE');
        

        $provider->attachListener($listener1, [EventA::class], 8);
        $provider->attachListener($listener2, [EventA::class], 9);
        $provider->attachListener($listener3, [EventA::class], 7);

        /**
         * Test invoke listeners
         * Test priority
         */
        $eventA = new EventA();
        foreach ($provider->getListenersForEvent($eventA) as $listener) {
            $listener($eventA);
        }
        $this->assertSame('HI THERE', join($eventA->accumulator));


        
        /**
         * Test count
         */
        // $provider->attachListener($listener2, [EventB::class]);
        // $provider->attachListener($listener3, [EventB::class]);

        // $listenersA = $provider->getListenersForEvent(new EventA());
        // $this->assertCount(3, iterator_to_array($listenersA, false));

        // $listenersB = $provider->getListenersForEvent(new EventB());
        // $this->assertCount(2, iterator_to_array($listenersB, false));

        // $listenersC = $provider->getListenersForEvent(new EventC());
        // $this->assertCount(0, iterator_to_array($listenersC, false));
        
        /**
         * Test Disableable
         */
        // $provider->disable();
        // $listenersB = $provider->getListenersForEvent(new EventB());
        // $this->assertCount(0, iterator_to_array($listenersB, false));
        // $provider->enable();

        /**
         * Test interfaces as tags
         */
        //$eventIC = new EventA();
        $eventB = new EventB();
        $listenerInterfaceIC = static fn ($event) => $event->add('IC');
        $listenerInterfaceIB = static fn ($event) => $event->add('IB');
        $listenerInterfaceIBC = static fn ($event) => $event->add('IBC');
        
        $provider->attachListener($listenerInterfaceIC, [IC::class], 199);
        $provider->attachListener($listenerInterfaceIB, [IC::class], 190);
        $provider->attachListener($listenerInterfaceIBC, [IB::class], 100);
        
        foreach ($provider->getListenersForEvent($eventB) as $listener) {
            $listener($eventB);
        }

        print_r($eventB);

        $this->assertSame('HI THERE IC', join($eventB->accumulator));        
        
        $listenersIC = $provider->getListenersForEvent($eventB);
        
        $this->assertCount(4, iterator_to_array($listenersIC, false));



    }

}

interface IA{};
interface IB{};
interface IC{};

class EventAsset implements DisableableInterface, ResettableInterface
{
    use DisableableTrait;
    public $accumulator = [];

    public function add($some) 
    {
        $this->accumulator[] = $some;
    }

    public function reset() 
    {
        $this->accumulator = [];
    }
}
class EventA extends EventAsset implements IC
{ 
    
}

class EventB extends EventAsset implements IB, IC
{
}

class EventC extends EventAsset implements IA
{
}
