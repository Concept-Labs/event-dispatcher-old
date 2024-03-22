<?php
declare(strict_types=1);
namespace Cl\EventDispatcher\Event;

use Cl\Context\Able\ContextableInterface;
use Cl\Context\Able\ContextableTrait;

class Event 
    implements 
        EventInterface,
        ContextableInterface
{
    use StoppableEventTrait;
    use ContextableTrait;
    
}