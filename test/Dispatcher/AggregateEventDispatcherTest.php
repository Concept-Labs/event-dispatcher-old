<?php
declare(strict_types=1);
namespace Cl\EventDispatcher\Test\Dispatcher;

use Cl\EventDispatcher\Dispatcher\AggregateEventDispatcher;
use Psr\EventDispatcher\EventDispatcherInterface;
use PHPUnit\Framework\TestCase;

/**
 * @covers Cl\EventDispatcher\Dispatcher\AggregateEventDispatcher
 */
class AggregateEventDispatcherTest extends TestCase
{
    public function testDispatchCallsAllAttachedDispatchers(): void
    {
        $event = new \stdClass();

        // Create mock dispatchers
        $dispatcher1 = $this->createMock(EventDispatcherInterface::class);
        $dispatcher1->expects($this->once())->method('dispatch')->with($event);

        $dispatcher2 = $this->createMock(EventDispatcherInterface::class);
        $dispatcher2->expects($this->once())->method('dispatch')->with($event);

        // Create AggregateEventDispatcher
        $aggregateDispatcher = new AggregateEventDispatcher();

        // Attach mock dispatchers
        $aggregateDispatcher->attachDispatcher($dispatcher1);
        $aggregateDispatcher->attachDispatcher($dispatcher2);

        // Dispatch the event
        $aggregateDispatcher->dispatch($event);
        $this->assertSame(true, true);
    }

    public function testAttachAndDetachDispatchers(): void
    {
        $event = new \stdClass();

        // Create mock dispatchers
        $dispatcher1 = $this->createMock(EventDispatcherInterface::class);
        $dispatcher2 = $this->createMock(EventDispatcherInterface::class);
        $dispatcher3 = $this->createMock(EventDispatcherInterface::class);

        // Create AggregateEventDispatcher
        $aggregateDispatcher = new AggregateEventDispatcher();

        
        $aggregateDispatcher->attachDispatcher($dispatcher1);
        $aggregateDispatcher->attachDispatcher($dispatcher2);

        // Dispatch the event
        $aggregateDispatcher->detachDispatcher($dispatcher2);
        

        // Check that only the first dispatcher is called
        // because the second dispatcher is not attached yet
        $dispatcher1->expects($this->once())->method('dispatch')->with($event);
        $dispatcher2->expects($this->never())->method('dispatch');

        // Attach the second dispatcher
        $dispatcher3->expects($this->once())->method('dispatch');
        $aggregateDispatcher->attachDispatcher($dispatcher3);

        // Dispatch the event again
        // Now both dispatchers should be called
        $aggregateDispatcher->dispatch($event);

        // Stub
        $this->assertSame(true, true);
    }
}
