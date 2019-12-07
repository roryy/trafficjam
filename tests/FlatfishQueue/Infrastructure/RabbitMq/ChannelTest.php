<?php

namespace FlatfishQueue\Infrastructure\RabbitMq;

use FlatfishQueue\Consumable;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Message\AMQPMessage;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

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

    public function setUp()
    {
        $this->amqpChannel = $this->createMock(AMQPChannel::class);

        $this->channel = new Channel($this->amqpChannel);
    }

    /**
     * @test
     */
    public function testChannelCanBeCreated()
    {
        $this->assertInstanceOf(Channel::class, $this->channel);
    }

    /**
     * @test
     */
    public function testChannelCanAcknowledge()
    {
        /** @var AMQPMessage|MockObject $msg */
        $msg = $this->createMock(AMQPMessage::class);
        $msg->expects($this->once())
            ->method('getDeliveryTag')
            ->willReturn(1);

        $this->amqpChannel
            ->expects($this->once())
            ->method('basic_ack')
            ->with(1);

        $this->channel->acknowledge($msg);
    }

    /**
     * @test
     */
    public function testChannelCanConsume()
    {
        $callback = function (Consumable $msg) {
            $this->assertSame(self::MESSAGE, $msg->getMessage());

            $msg->acknowledge();
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
                function ($message) use ($callback) {
                    $message = new ConsumeMessage($message, $this->channel);

                    call_user_func($callback, $message);
                }
            )
            ->will(
                $this->returnCallback(
                    function ($queue , $consumer_tag, $no_local, $no_ack, $exclusive, $nowait, $callback, $ticket, $arguments) {
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
    public function testChannelCanPublish()
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
    public function testChannelCanBeDisconnect()
    {
        $this->amqpChannel
            ->expects($this->once())
            ->method('close');

        $this->channel->disconnect();
    }
}