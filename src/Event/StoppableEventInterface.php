<?php
declare(strict_types=1);
namespace Cl\EventDispatcher\Event;


use Psr\EventDispatcher\StoppableEventInterface as PsrStoppableEventInterface;

interface StoppableEventInterface extends PsrStoppableEventInterface
{
    /**
     * Set the propagation is stopped
     *
     * @param boolean $isStopped Stopped flag
     * 
     * @return void
     */
    function setIsPropagationStopped(bool $isStopped): void;
}