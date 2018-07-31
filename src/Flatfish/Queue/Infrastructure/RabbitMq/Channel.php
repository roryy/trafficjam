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

namespace Flatfish\Queue\Infrastructure\RabbitMq;

use Flatfish\Queue\ConnectionInterface;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Message\AMQPMessage;

class Channel implements ChannelInterface
{
    /**
     * @var ConnectionInterface
     */
    protected $connection;

    /**
     * @var AMQPChannel
     */
    protected $channel;

    public function __construct(ConnectionInterface $connection, AMQPChannel $channel)
    {
        $this->connection = $connection;
        $this->channel = $channel;
    }

    public function getConnection(): ConnectionInterface
    {
        return $this->connection;
    }

    public function acknowledge(AMQPMessage $msg): void
    {
        $this->channel->basic_ack($msg->delivery_info['delivery_tag']);
    }

    public function consume(RabbitMqQueue $consumer, callable $callback): void
    {
        $callback = function ($message) use ($callback) {
            $message = new ConsumeMessage($message, $this);

            call_user_func($callback, $message);
        };

        $this->channel->basic_consume(
            $consumer->getName(),
            '',
            false,
            false,
            false,
            false,
            $callback
        );

        while (count($this->channel->callbacks)) {
            $this->channel->wait();
        }
    }

    public function publish(PublishMessage $publisher): void
    {
        $message = new AMQPMessage($publisher->getMessage());

        $this->channel->basic_publish($message, $publisher->getExchange(), $publisher->getRoutingKey());
    }
}
