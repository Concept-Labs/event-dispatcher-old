<?php
declare(strict_types=1);


namespace Cl\EventDispatcher\Test\ListenerProvider;

use PHPUnit\Framework\TestCase;
use Cl\EventDispatcher\ListenerProvider\Aggregate\TaggedPriorityAggregateProvider;
use Psr\EventDispatcher\ListenerProviderInterface;

/**
 * @covers Cl\EventDispatcher\ListenerProvider\Aggregate\TaggedPriorityAggregateListenerProvider
 */
class TaggedPriorityAggregateProviderTest extends TestCase
{
    protected TaggedPriorityAggregateProvider $aggregateProvider;
    public function setUp(): void
    {
        $this->aggregateProvider = new TaggedPriorityAggregateProvider();
    }
    public function testGetListenersForEvent()
    {
        $this->aggregateProvider->reset();
        // Arrange
        $event = new class {
            public $handled = [];
        } ;

        $providerOne = new class implements ListenerProviderInterface {
            public $id = 'provider 1';
            public function getListenersForEvent(object $event): iterable
            {
                yield function (object $event) {
                    $event->handled[] = 'handled by One';
                };
            }
        };
        
        $providerTwo = new class implements ListenerProviderInterface {
            public $id = 'provider 2';
            public $listener = null;
            public function getListenersForEvent(object $event): iterable {
                yield function (object $event) {
                    $event->handled[] = 'handled by Two once';
                };
                yield function (object $event) {
                    $event->handled[] = 'handled by Two twice';
                };
                yield function (object $event) {
                    $event->handled[] = 'handled by Two third time';
                };
            }
        };


        $providerSub = new TaggedPriorityAggregateProvider();


        $providerThree = new class implements ListenerProviderInterface {
            public $id = 'provider 3';
            public $listener = null;
            public function getListenersForEvent(object $event): iterable {
                yield function (object $event) {
                    $event->handled[] = 'handled by Three here';
                };
            }
        };
        $providerSub->attachProvider($providerThree);
        $providerSub->attachProvider($providerTwo);

        
        $this->aggregateProvider->attachProvider($providerOne, 77, ['tagOne', 'tagOneTwo']);
        $this->aggregateProvider->attachProvider($providerTwo, 33, ['tagTwo', 'tagOneTwo']);
        $this->aggregateProvider->attachProvider($providerThree, 33, ['tagThree']);
        $this->aggregateProvider->attachProvider($providerSub, 33, ['tagThree']);
        //$this->aggregateProvider->attachProvider($providerTwo, 44, ['tagOne', 'tagOneTwo']);
        // $this->aggregateProvider->attachProvider($providerThree, 22);
        // Act
        foreach ($this->aggregateProvider->getListenersForEvent($event, ['tagThree']) as $listener) {
            //print_r(count($listeners));
            //foreach ($listeners as $listener) {
                $listener($event);
            //}
        }
        print_r($event);
        
        $this->assertSame($providerTwo->getListenersForEvent($event), $listeners[0]);
        // $listeners[0]($event);
        // $listeners[0]($event);
        // var_dump($event);

        // Assert
        //$this->assertEquals([$providerOne->getListenersForEvent(new \stdClass)], $listeners);
    }

    public function testAddProvider()
    {
        // Arrange
        $provider = $this->createMock(ListenerProviderInterface::class);

        // Act
        $aggregateProvider = new AggregateListenerProvider();
        $aggregateProvider->addProvider($provider);

        // Assert
        // You can add assertions here to verify that the provider was added successfully.
        $this->assertTrue(true);
    }
}
