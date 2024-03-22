<?php
declare(strict_types=1);
namespace Cl\EventDispatcher\Event;

use Cl\Able\Taggable\TaggableInterface;
use Cl\Able\Taggable\TaggableTrait;

class TaggableEvent extends Event implements TaggableInterface
{
    use TaggableTrait;
}