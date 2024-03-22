<?php
declare(strict_types=1);
namespace Cl\EventDispatcher;



use ArrayIterator;
use Cl\Able\Callable\CallableTrait;
use Psr\EventDispatcher\ListenerProviderInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;

use Cl\Able\Disableable\DisableableInterface;
use Cl\Able\Disableable\DisableableTrait;

use Cl\EventDispatcher\ListenerProvider\Exception\InvalidArgumentException;

/**
 * Class ListenerProvider
 *
 * @package Cl\EventDispatcher
 */
abstract class ListenerProviderAbstract 
    implements 
        ListenerProviderInterface,
        ListenerProviderAttachableInterface,
        LoggerAwareInterface,
        DisableableInterface
{
    use LoggerAwareTrait;
    use DisableableTrait;
    use CallableTrait;

    
    /**
     * {@inheritDoc}
     */
    abstract public function getListenersForEvent(object $event): iterable;
    /**
     * Attach a listener
     * The argument type is not stricted because on this stage 
     * argument can contains non loaded class name
     *
     * @param callable $listener The callable listener
     * @param string   $event    The event name. Name kept ampty in abstract class
     * 
     * @return void
     */
    public function attachListener($listener, string $event = ''): void
    {
        //@TODO move to config
        $trigger_autoload = true;
        $this->assertIsCallable($listener, $trigger_autoload);
     
    }

    /**
     * Check callable
     *
     * @param mixed $callable 
     * @param bool  $trigger_auoload 
     * 
     * @return void
     */
    protected function assertIsCallable($callable, bool $trigger_auoload = true) : void
    {
        if (!$this->isCallable($callable, $trigger_auoload)) {
            throw new InvalidArgumentException(_('Provided listener is not callable'));
        }
    }

}