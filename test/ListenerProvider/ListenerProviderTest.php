<?php
declare(strict_types=1);
namespace Cl\EventDispatcher\Test\ListenerProvider;

use Cl\EventDispatcher\ListenerProvider;
use PHPUnit\Framework\TestCase;
use Psr\SimpleCache\CacheInterface;

/**
 * Class ListenerProviderTest
 *
 * @covers  Cl\EventDispatcher\ListenerProvider
 */
class ListenerProviderTest extends TestCase
{

/**
     * @var ListenerProvider An instance of ListenerProvider without cache.
     */    private ListenerProvider $listenerProvider;

     /**
     * Set up the test environment.
     */
    protected function setUp(): void
    {
        $cache = $this->createMock(CacheInterface::class);
        $this->listenerProvider = new ListenerProvider();
    }

    /**
     * Test attach method.
     */
    public function testAttach()
    {
        $event = new \stdClass();
        $listener = function () {};

        $this->listenerProvider->attachListener($event, $listener);

        $listeners = $this->listenerProvider->getListenersForEvent($event);
        $this->assertCount(1, $listeners);
        $this->assertEquals($listener, $listeners[0]);
    }

    /**
     * Test sort method.
     */
    public function testSort()
    {
        $event = new \stdClass();
        $listener1 = function () {return 4;};
        $listener2 = function () {return 3;};
        $listener3 = function () {return 1;};

        $this->listenerProvider->attachListener($event, $listener1, 1);
        $this->listenerProvider->attachListener($event, $listener2, 3);
        $this->listenerProvider->attachListener($event, $listener3, 2);

        $listeners = $this->listenerProvider->getListenersForEvent($event);
        $this->assertEquals([$listener2, $listener3, $listener1], $listeners);
    }

    /**
     * Test detach method.
     */
    public function testDetach()
    {
        $event = new \stdClass();
        $listener = function () {};

        $this->listenerProvider->attachListener($event, $listener);
        $this->listenerProvider->attachListener($event, $listener);

        $this->listenerProvider->detach($listener);

        $listeners = $this->listenerProvider->getListenersForEvent($event);
        $this->assertCount(0, $listeners);
        $this->assertEquals(0, $this->listenerProvider->count());
    }

    /**
     * Test count method.
     */
    public function testCount()
    {
        $event = new \stdClass();
        $listener = function () {};

        $this->listenerProvider->attachListener($event, $listener);
        $this->listenerProvider->attachListener($event, $listener);
        $this->listenerProvider->attachListener($event, $listener);

        $this->assertEquals(3, $this->listenerProvider->count());
    }
}