<?php

namespace Cl\EventDispatcher\Test\Dispatcher;

use PHPUnit\Framework\TestCase;
use Cl\EventDispatcher\EventDispatcher;
use Cl\EventDispatcher\Dispatcher\Exception\EventPropagationIsStoppedException;
use Cl\EventDispatcher\Test\Asset\EventTest;
use Cl\EventDispatcher\Test\Asset\EventTestStoppable;
use Psr\EventDispatcher\ListenerProviderInterface;
use Psr\Log\LoggerInterface;

/**
 * @covers Cl\EventDispatcher\EventDispatcher
 */
class EventDispatcherTest extends TestCase
{
    public function testAddAndDispatchListener()
    {
        $listenerProvider = $this->createMock(ListenerProviderInterface::class);
        $logger = $this->createMock(LoggerInterface::class);

        $eventDispatcher = new EventDispatcher($listenerProvider, null, $logger);

        
        $event = new EventTest();
        $listener = static function ($event) {
            $event->handled = true;
        };
        $listenerProvider->method('getListenersForEvent')->willReturn([$listener]);
        
        
        $resultEvent = $eventDispatcher->dispatch($event);

        
        $this->assertTrue($event->handled);
        
        $this->assertSame($event, $resultEvent);
    }

    public function testEventPropagationIsStopped()
    {
        $listenerProvider = $this->createMock(ListenerProviderInterface::class);
        $logger = $this->createMock(LoggerInterface::class);

        $eventDispatcher = new EventDispatcher($listenerProvider, null, $logger);

        $event = new EventTestStoppable();
        $event->handledStr = 'not handled';

        $listener = static function (&$event) {
            $event->stopped = true;
        };

        $listener2 = static function (&$event) {
            $event->handledStr = 'handled by listener2';
        };
        $listenerProvider->method('getListenersForEvent')->willReturn([$listener, $listener2]);

        $resultEvent = $eventDispatcher->dispatch($event);

        $this->assertEquals('not handled', $event->handledStr);
        $this->assertSame($event, $resultEvent);
    }

    public function testEventPropagationIsStoppedExceptionInsideListener()
    {
        $listenerProvider = $this->createMock(ListenerProviderInterface::class);
        $logger = $this->createMock(LoggerInterface::class);

        $eventDispatcher = new EventDispatcher($listenerProvider, null, $logger);

        
        $listener = static function ($event) {
            throw new EventPropagationIsStoppedException();
        };
        $listenerProvider->method('getListenersForEvent')->willReturn([$listener]);

        $event = new EventTestStoppable();

        $resultEvent = $eventDispatcher->dispatch($event);

        $this->assertSame($event, $resultEvent);
    }

}
