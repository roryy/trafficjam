<?php

namespace Flatfish\Queue;

interface Queue
{
    /**
     * @param string $message
     */
    public function publish($message);

    /**
     * @param callable $callback
     */
    public function consume($callback);
}
