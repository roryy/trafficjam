<?php
/**
 * Flatfish Queue
 *
 * @author Rory Scholman <rory@roryy.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Flatfish\Queue\Infrastructure\RabbitMq;

use Flatfish\Queue\Consumable;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;

class RabbitMqQueueTest extends TestCase
{
    public function test_initialization()
    {
        $connection = $this->prophesize(Connection::class);

        $rabbitMqQueue = new RabbitMqQueue(
            $connection->reveal(),
            'test_queue',
            'test_exchange'
        );

        $this->assertInstanceOf(RabbitMqQueue::class, $rabbitMqQueue);
    }

    public function test_publish()
    {
        $channel = $this->prophesize(Channel::class);
        $channel->publish(Argument::any())->shouldBeCalled();

        $connection = $this->prophesize(Connection::class);
        $connection->isConnected()->willReturn(true);
        $connection->disconnect()->shouldBeCalled();
        $connection->getChannel()->willReturn($channel);

        $rabbitMqQueue = new RabbitMqQueue(
            $connection->reveal(),
            'test_queue',
            'test_exchange'
        );

        $this->assertNull($rabbitMqQueue->publish('test'));
    }

    /**
     * @expectedException \Flatfish\Queue\Exception\NoConnectionException
     * @expectedExceptionMessage No connection with RabbitMQ
     */
    public function test_no_connection_exception_when_publish()
    {
        $connection = $this->prophesize(Connection::class);
        $connection->isConnected()->willReturn(false);
        $connection->disconnect()->shouldBeCalled();

        $rabbitMqQueue = new RabbitMqQueue(
            $connection->reveal(),
            'test_queue',
            'test_exchange'
        );

        $rabbitMqQueue->publish('test');
    }

    public function test_consume()
    {
        $callback = function (Consumable $msg) {
            print $msg->getMessage();
        };

        $channel = $this->prophesize(Channel::class);
        $channel->consume(Argument::any(), $callback)->shouldBeCalled();

        $connection = $this->prophesize(Connection::class);
        $connection->isConnected()->willReturn(true);
        $connection->getChannel()->willReturn($channel);
        $connection->disconnect()->shouldBeCalled();

        $rabbitMqQueue = new RabbitMqQueue(
            $connection->reveal(),
            'test_queue',
            'test_exchange'
        );

        $this->assertNull($rabbitMqQueue->consume($callback));

        $rabbitMqQueue->__destruct();
    }

    /**
     * @expectedException \Flatfish\Queue\Exception\NoConnectionException
     * @expectedExceptionMessage No connection with RabbitMQ
     */
    public function test_no_connection_exception_when_when_consume()
    {
        $connection = $this->prophesize(Connection::class);
        $connection->isConnected()->willReturn(false);
        $connection->disconnect()->shouldBeCalled();

        $rabbitMqQueue = new RabbitMqQueue(
            $connection->reveal(),
            'test_queue',
            'test_exchange'
        );

        $rabbitMqQueue->consume(function () {
        });
    }
}
