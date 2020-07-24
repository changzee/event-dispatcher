<?php

declare(strict_types=1);

namespace ChangZee\EventDispatcher;

abstract class EventSubscriber
{
    /***
     * The event handler mappings for the application
     * @return array
     */
    abstract public function subscribe() : array;

    /***
     * Register an event listener with the provider.
     * @param ListenerProvider $provider
     */
    public function attachSubscriber(ListenerProvider $provider) : void
    {
        foreach ($this->subscribe() as $eventName => $method) {
            if (is_array($method)) {
                list($method, $priority) = $method;
                $provider->attach($eventName, [$this, $method], $priority);
            } else {
                $provider->attach($eventName, [$this, $method]);
            }
        }
    }
}
