<?php
/**
 * Traffic jam
 *
 * @author Rory Scholman <rory@roryy.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Trafficjam\RabbitMq;

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Message\AMQPMessage;
use Trafficjam\BasicConsumableMessage;
use Trafficjam\ConsumableMessageInterface;

class Channel
{
    private AMQPChannel $channel;

    public function __construct(AMQPChannel $channel)
    {
        $this->channel = $channel;
    }

    public function acknowledge(int $deliveryTag): void
    {
        $this->channel->basic_ack($deliveryTag);
    }

    public function consume(string $name, callable $callback): void
    {
        $callback = function (AMQPMessage $message) use ($callback) {
            $message = new BasicConsumableMessage($message->getBody(), (string) $message->getDeliveryTag());

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

    public function pop(string $name): ConsumableMessageInterface
    {
        /** @var AMQPMessage $message */
        $message = $this->channel->basic_get($name);

        return new BasicConsumableMessage($message->getBody(), (string) $message->getDeliveryTag());
    }

    public function publish(PublishMessage $publisher): void
    {
        $message = new AMQPMessage($publisher->getMessage());

        $this->channel->basic_publish($message, $publisher->getExchange(), $publisher->getRoutingKey());
    }

    public function declareQueue(string $name, bool $durable): void
    {
        $this->channel->queue_declare($name, false, $durable);
    }

    public function disconnect(): void
    {
        $this->channel->close();
    }
}
