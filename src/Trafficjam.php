<?php

namespace Trafficjam;

class Trafficjam
{
    private QueueInterface $queue;

    public function __construct(QueueInterface $queue)
    {
        $this->queue = $queue;
    }

    public function getQueue(): QueueInterface
    {
        return $this->queue;
    }

    public function publish(string $message): void
    {
        $this->queue->publish($message);
    }

    public function consume(callable $callback): void
    {
        $this->queue->consume($callback);
    }

    /**
     * @throws QueueIsEmptyException
     */
    public function pop(): ConsumableMessageInterface
    {
        return $this->queue->pop();
    }
    
    public function acknowledge(ConsumableMessageInterface $message): void
    {
        $this->queue->acknowledge($message);
    }
}
