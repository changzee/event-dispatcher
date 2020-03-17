<?php

declare(strict_types=1);

namespace JigsawPuzzles\EventDispatcher;

abstract class EventSubscriber
{
    /***
     * The event handler mappings for the application
     * @return array
     */
    abstract public function events() : array;

    /***
     * Register an event listener with the provider.
     * @param ListenerProvider $provider
     */
    public function attachSubscriber(ListenerProvider $provider)
    {
        foreach ($this->events() as $eventName => $method) {
            if (is_array($method)) {
                list($method, $priority) = $method;
                $provider->attach($eventName, [$this, $method], $priority);
            } else {
                $provider->attach($eventName, [$this, $method]);
            }
        }
    }
}
