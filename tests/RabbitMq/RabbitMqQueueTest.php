<?php
/**
 * Traffic jam
 *
 * @author Rory Scholman <rory@roryy.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Trafficjam\Test\RabbitMq;

use Trafficjam\BasicConsumableMessage;
use Trafficjam\ConsumableMessageInterface;
use Trafficjam\NoConnectionException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Trafficjam\RabbitMq\Channel;
use Trafficjam\RabbitMq\Connection;
use Trafficjam\RabbitMq\PublishMessage;
use Trafficjam\RabbitMq\RabbitMqQueue;

class RabbitMqQueueTest extends TestCase
{
    const TEST_MESSAGE = 'test message';
    const QUEUE_NAME = 'test';

    /**
     * @var Connection|MockObject
     */
    private $connection;
    /**
     * @var RabbitMqQueue
     */
    private $queue;

    public function setUp(): void
    {
        $this->connection = $this->createMock(Connection::class);

        $this->queue = new RabbitMqQueue(
            $this->connection,
            self::QUEUE_NAME
        );
    }

    /**
     * @test
     */
    public function testQueueCanBeCreated(): void
    {
        $this->assertInstanceOf(RabbitMqQueue::class, $this->queue);
    }

    /**
     * @test
     */
    public function testQueueMessageCanBePublishedWithExchangeAndRoutingKey(): void
    {
        $this->connect();

        /** @var Channel|MockObject $channel */
        $channel = $this->createMock(Channel::class);

        $channel->expects($this->once())
            ->method('declareQueue')
            ->with(self::QUEUE_NAME, true);

        $channel->expects($this->once())
            ->method('publish')
            ->with(new PublishMessage(
                self::TEST_MESSAGE,
                self::QUEUE_NAME
            ));

        $this->connection
            ->expects($this->exactly(2))
            ->method('getChannel')
            ->willReturn($channel);

        $this->queue->publish(self::TEST_MESSAGE);
    }

    /**
     * @test
     */
    public function testQueueMessageCannotBePublishedWithoutConnection(): void
    {
        $this->expectException(NoConnectionException::class);
        $this->expectExceptionMessage('No connection with RabbitMQ');

        $this->connect(false);

        $this->connection
            ->expects($this->never())
            ->method('getChannel');

        $this->queue->publish(self::TEST_MESSAGE);
    }

    /**
     * @test
     */
    public function testQueueMessageCanBeConsumed(): void
    {
        $this->connect();

        $callback = function (ConsumableMessageInterface $msg) {
        };

        /** @var Channel|MockObject $channel */
        $channel = $this->createMock(Channel::class);

        $channel->expects($this->once())
            ->method('declareQueue')
            ->with(self::QUEUE_NAME, true);

        $channel->expects($this->once())
            ->method('consume')
            ->with(
                self::QUEUE_NAME,
                $callback
            );

        $this->connection
            ->expects($this->exactly(2))
            ->method('getChannel')
            ->willReturn($channel);

        $this->queue->consume($callback);
    }

    /**
     * @test
     */
    public function testQueueMessageCanBeAcknowledge(): void
    {
        /** @var Channel|MockObject $channel */
        $channel = $this->createMock(Channel::class);
        $channel->expects($this->once())
            ->method('acknowledge')
            ->with(1);

        $this->connection
            ->expects($this->once())
            ->method('getChannel')
            ->willReturn($channel);

        $this->queue->acknowledge(
            new BasicConsumableMessage(self::TEST_MESSAGE, '1')
        );
    }

    /**
     * @test
     */
    public function testQueuePopReturnsMessage(): void
    {
        $consumeMessage = new BasicConsumableMessage(self::TEST_MESSAGE, '1');

        /** @var Channel|MockObject $channel */
        $channel = $this->createMock(Channel::class);
        $channel->expects($this->once())
            ->method('pop')
            ->with(self::QUEUE_NAME)
            ->willReturn($consumeMessage);

        $this->connection
            ->expects($this->once())
            ->method('getChannel')
            ->willReturn($channel);

        $this->assertSame(
            $consumeMessage,
            $this->queue->pop()
        );
    }

    /**
     * @test
     */
    public function testQueueCanBeDisconnect(): void
    {
        $this->connection
            ->expects($this->once())
            ->method('disconnect');

        $this->queue->disconnect();
    }

    private function connect(bool $isConnected = true): void
    {
        $this->connection
            ->expects($this->once())
            ->method('isConnected')
            ->willReturn($isConnected);
    }
}
