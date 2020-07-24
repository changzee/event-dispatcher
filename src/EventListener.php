<?php

declare(strict_types=1);

namespace ChangZee\EventDispatcher;

abstract class EventListener
{
    /***
     * @var int
     */
    public $priority = 50;

    /***
     * The listening events.
     * @return array
     */
    abstract public function listen() : array;

    /***
     * the event handler
     * @param object $event
     */
    abstract public function handle(object $event) : void;

    /***
     * Register an event listener with the provider.
     * @param ListenerProvider $provider
     */
    public function attachListener(ListenerProvider $provider) : void
    {
        foreach ($this->listen() as $event) {
            $priority = $this->priority;

            if (is_array($event)) {
                list($eventName, $priority) = $event;
                $provider->attach($eventName, [$this, 'handle'], $priority);
            } else {
                $provider->attach($event, [$this, 'handle'], $priority);
            }
        }
    }
}
