<?php

declare(strict_types=1);

namespace JigsawPuzzles\EventDispatcher;

use Psr\EventDispatcher\ListenerProviderInterface;

/**
 * Class ListenerProvider
 * @package adBrand\Event
 */
class ListenerProvider implements ListenerProviderInterface
{

    protected $listened = [];

    /***
     * @inheritdoc
     */
    public function getListenersForEvent(object $event) : iterable
    {
        $queue = new \SplPriorityQueue();
        foreach ($this->listened as $class => $listeners) {
            if ($event instanceof $class && count($listeners)) {
                foreach ($listeners as $ele) {
                    list ($callable, $priority) = $ele;
                    $queue->insert($callable, $priority);
                }
            }
        }
        return $queue;
    }

    /**
     * Attach an event handler for the given event name
     * @param  string   $eventName the class name of the event.
     * @param  callable $callable  callable MUST be type-compatible with $even.
     * @param  int      $priority  priority, bigger number means higher priority to execute. By
     *                             the way, priority only work in the same dispatcher.
     * @return $this
     */
    public function attach(string $eventName, callable $callable, int $priority = 50) : self
    {
        $this->listened[$eventName][] = [$callable, $priority];
        return $this;
    }

    /**
     *  Detach all event handlers registered for an interface
     * @param  string   $eventName the class name of the event.
     * @return $this
     */
    public function detach(string $eventName) : self
    {
        unset($this->listened[$eventName]);
        return $this;
    }

}