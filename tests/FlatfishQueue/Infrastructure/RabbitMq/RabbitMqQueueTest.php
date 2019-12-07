<?php
/**
 * Flatfish Queue
 *
 * @author Rory Scholman <rory@roryy.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlatfishQueue\Infrastructure\RabbitMq;

use FlatfishQueue\Consumable;
use FlatfishQueue\NoConnectionException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;

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

    public function setUp()
    {
        $this->connection = $this->createMock(Connection::class);

        $this->queue = new RabbitMqQueue(
            $this->connection,
            self::QUEUE_NAME
        );
        $this->queue->setLogger(new NullLogger());
    }

    /**
     * @test
     */
    public function testQueueCanBeCreated()
    {
        $this->assertInstanceOf(RabbitMqQueue::class, $this->queue);
    }

    /**
     * @test
     */
    public function testQueueMessageCanBePublishedWithExchangeAndRoutingKey()
    {
        $this->connect();

        /** @var Channel|MockObject $channel */
        $channel = $this->createMock(Channel::class);
        $channel->expects($this->once())
            ->method('publish')
            ->with(new PublishMessage(
                self::TEST_MESSAGE,
                'routing',
                'exchange'
            ));

        $this->connection
            ->expects($this->once())
            ->method('getChannel')
            ->willReturn($channel);

        $this->queue->withExchange('exchange');
        $this->queue->withRoutingKey('routing');
        $this->queue->publish(self::TEST_MESSAGE);
    }

    /**
     * @test
     */
    public function testQueueMessageCannotBePublishedWithoutConnection()
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
    public function testQueueMessageCanBeConsumed()
    {
        $this->connect();

        $callback = function (Consumable $msg) {};

        /** @var Channel|MockObject $channel */
        $channel = $this->createMock(Channel::class);
        $channel->expects($this->once())
            ->method('consume')
            ->with(
                self::QUEUE_NAME,
                $callback
            );

        $this->connection
            ->expects($this->once())
            ->method('getChannel')
            ->willReturn($channel);

        $this->queue->consume($callback);
    }

    /**
     * @test
     */
    public function testQueueCanBeDisconnect()
    {
        $this->connection
            ->expects($this->once())
            ->method('disconnect');

        $this->queue->disconnect();
    }

    private function connect(bool $isConnected = true)
    {
        $this->connection
            ->expects($this->once())
            ->method('isConnected')
            ->willReturn($isConnected);
    }
}
