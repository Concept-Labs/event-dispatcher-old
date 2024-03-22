<?php
declare(strict_types=1);
namespace Cl\EventDispatcher\ListenerProvider\Aggregate;

use Psr\EventDispatcher\ListenerProviderInterface;

interface AggregateListenerProviderInterface
{
    public function attachProvider(ListenerProviderInterface $provider): void;
    public function detachProvider(ListenerProviderInterface $provider): void;
}