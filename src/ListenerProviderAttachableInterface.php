<?php
declare(strict_types=1);
namespace Cl\EventDispatcher;

use Psr\EventDispatcher\ListenerProviderInterface;

interface ListenerProviderAttachableInterface extends ListenerProviderInterface
{
    const TAG_USE_PARENT_CLASSES = false;

    function attachListener(callable $listener);
}