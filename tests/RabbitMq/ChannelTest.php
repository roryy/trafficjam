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
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Message\AMQPMessage;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Trafficjam\RabbitMq\Channel;
use Trafficjam\RabbitMq\PublishMessage;

class ChannelTest extends TestCase
{
    const MESSAGE = 'test';
    const NAME = 'test';

    /**
     * @var AMQPChannel|MockObject
     */
    private $amqpChannel;

    /**
     * @var Channel
     */
    private $channel;

    public function setUp(): void
    {
        $this->amqpChannel = $this->createMock(AMQPChannel::class);

        $this->channel = new Channel($this->amqpChannel);
    }

    /**
     * @test
     */
    public function testChannelCanBeCreated(): void
    {
        $this->assertInstanceOf(Channel::class, $this->channel);
    }

    /**
     * @test
     */
    public function testChannelCanAcknowledge(): void
    {
        $this->amqpChannel
            ->expects($this->once())
            ->method('basic_ack')
            ->with(1);

        $this->channel->acknowledge(1);
    }

    /**
     * @test
     */
    public function testChannelCanConsume(): void
    {
        $callback = function (ConsumableMessageInterface $msg) {
            $this->assertSame(self::MESSAGE, $msg->getMessage());
            $this->assertSame('1', $msg->getId());
        };

        $this->amqpChannel
            ->expects($this->once())
            ->method('basic_consume')
            ->with(
                self::NAME,
                '',
                false,
                false,
                false,
                false,
                function (AMQPMessage $message) use ($callback) {
                    $message = new BasicConsumableMessage($message->body, $message->getDeliveryTag());

                    call_user_func($callback, $message);
                }
            )
            ->will(
                $this->returnCallback(
                    function ($queue, $consumer_tag, $no_local, $no_ack, $exclusive, $nowait, $callback, $ticket, $arguments) {
                        /** @var AMQPMessage|MockObject $msg */
                        $msg = $this->createMock(AMQPMessage::class);
                        $msg->expects($this->once())
                            ->method('getDeliveryTag')
                            ->willReturn(1);
                        $msg->expects($this->once())
                            ->method('getBody')
                            ->willReturn(self::MESSAGE);

                        call_user_func($callback, $msg);
                    }
                )
            );

        $this->amqpChannel
            ->expects($this->exactly(2))
            ->method('is_consuming')
            ->willReturnOnConsecutiveCalls(true, false);

        $this->channel
            ->consume(self::NAME, $callback);
    }

    /**
     * @test
     */
    public function testChannelCanPublish(): void
    {
        $this->amqpChannel
            ->expects($this->once())
            ->method('basic_publish')
            ->with(
                new AMQPMessage(self::MESSAGE),
                'exchange',
                'routing'
            );

        $this->channel->publish(new PublishMessage(
            self::MESSAGE,
            'routing',
            'exchange'
        ));
    }

    /**
     * @test
     */
    public function testChannelCanBeDisconnect(): void
    {
        $this->amqpChannel
            ->expects($this->once())
            ->method('close');

        $this->channel->disconnect();
    }
}
