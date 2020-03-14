<?php

declare(strict_types=1);

namespace JigsawPuzzles\EventDispatcher;

use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\ListenerProviderInterface;
use Psr\EventDispatcher\StoppableEventInterface;
use RuntimeException;

class EventDispatcher implements EventDispatcherInterface
{
    /**
     * @var ListenerProvider[]
     */
    protected $providers = [];

    /**
     * Dispatcher constructor.
     * @param  ListenerProviderInterface ...$providers
     */
    public function __construct(ListenerProviderInterface ...$providers)
    {
        foreach ($providers as $provider) {
            $hash = \spl_object_hash($provider);
            if (!isset($this->providers[$hash])) {
                $this->providers[$hash] = $provider;
            } else {
                throw new RuntimeException("Provider duplicated");
            }
        }
    }

    /***
     * @inheritdoc
     */
    public function dispatch(object $event)
    {
        foreach ($this->getListeners($event) as $listener) {
            if ($event instanceof StoppableEventInterface && $event->isPropagationStopped()) {
                return $event;
            }
            $listener($event);
        }
        return $event;
    }

    /**
     * @param  object $event
     * @return iterable
     */
    protected function getListeners(object $event): iterable
    {
        foreach ($this->providers as $provider) {
            yield $provider->getListenersForEvent($event);
        }
    }

}