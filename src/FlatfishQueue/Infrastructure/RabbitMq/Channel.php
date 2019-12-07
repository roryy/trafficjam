<?php
/**
 * Flatfish Queue
 *
 * @author Rory Scholman <rory@roryy.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace FlatfishQueue\Infrastructure\RabbitMq;

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Message\AMQPMessage;

class Channel
{
    /**
     * @var AMQPChannel
     */
    private $channel;

    public function __construct(AMQPChannel $channel)
    {
        $this->channel = $channel;
    }

    public function acknowledge(AMQPMessage $msg): void
    {
        $this->channel->basic_ack($msg->getDeliveryTag());
    }

    public function consume(string $name, callable $callback): void
    {
        $callback = function ($message) use ($callback) {
            $message = new ConsumeMessage($message, $this);

            call_user_func($callback, $message);
        };

        $this->channel->basic_consume(
            $name,
            '',
            false,
            false,
            false,
            false,
            $callback
        );

        while ($this->channel->is_consuming()) {
            $this->channel->wait();
        }
    }

    public function publish(PublishMessage $publisher): void
    {
        $message = new AMQPMessage($publisher->getMessage());

        $this->channel->basic_publish($message, $publisher->getExchange(), $publisher->getRoutingKey());
    }

    public function disconnect(): void
    {
        $this->channel->close();
    }
}
