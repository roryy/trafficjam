<?php

namespace Trafficjam;

class Trafficjam
{
    /**
     * @var Queue
     */
    private $queue;

    public function __construct(Queue $queue)
    {
        $this->queue = $queue;
    }

    public function getQueue(): Queue
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
     * @throws QueueIsEmpty
     */
    public function pop(): ConsumeMessage
    {
        return $this->queue->pop();
    }
    
    public function acknowledge(ConsumeMessage $message): void
    {
        $this->queue->acknowledge($message);
    }
}
