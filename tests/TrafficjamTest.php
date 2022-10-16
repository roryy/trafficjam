<?php

namespace Trafficjam\Test;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Trafficjam\BasicConsumableMessage;
use Trafficjam\QueueInterface;
use Trafficjam\Trafficjam;

class TrafficjamTest extends TestCase
{
    private const MESSAGE = 'test';

    /**
     * @var MockObject|QueueInterface
     */
    private $queue;
    /**
     * @var Trafficjam
     */
    private $trafficjam;

    public function setUp(): void
    {
        $this->queue = $this->createMock(QueueInterface::class);
        $this->trafficjam = new Trafficjam($this->queue);
    }

    /**
     * @test
     */
    public function getQueueReturnsQueue(): void
    {
        $this->assertSame(
            $this->queue,
            $this->trafficjam->getQueue()
        );
    }

    /**
     * @test
     */
    public function publishCallsPublishInQueue(): void
    {
        $this->queue
            ->expects($this->once())
            ->method('publish')
            ->with(self::MESSAGE);

        $this->trafficjam
            ->publish(self::MESSAGE);
    }

    /**
     * @test
     */
    public function consumehCallsConsumeInQueue(): void
    {
        $callback = function () {
            echo self::MESSAGE;
        };

        $this->queue
            ->expects($this->once())
            ->method('consume')
            ->with($callback);

        $this->trafficjam
            ->consume($callback);
    }

    /**
     * @test
     */
    public function popReturnsConsumeMessage(): void
    {
        $consumeMessage = new BasicConsumableMessage(self::MESSAGE, 1);

        $this->queue
            ->expects($this->once())
            ->method('pop')
            ->willReturn($consumeMessage);

        $this->assertSame(
            $consumeMessage,
            $this->trafficjam->pop()
        );
    }

    /**
     * @test
     */
    public function acknowledgeCallsAcknowledgeInQueue(): void
    {
        $consumeMessage = new BasicConsumableMessage(self::MESSAGE, 1);

        $this->queue
            ->expects($this->once())
            ->method('acknowledge')
            ->with($consumeMessage);

        $this->trafficjam
            ->acknowledge($consumeMessage);
    }
}
