<?php
/**
 * Flatfish Queue
 *
 * @author Rory Scholman <rory@roryy.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Flatfish\Queue;

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

    public function getConnection()
    {
        return $this->connection;
    }

    public function acknowledge(AMQPMessage $msg)
    {
        $this->channel->basic_ack($msg->delivery_info['delivery_tag']);
    }

    public function consume(ConsumerInterface $consumer, $callback)
    {
        $this->channel->basic_consume($consumer->getName(), '', false, false, false, false, $callback);

        while (count($this->channel->callbacks)) {
            $this->channel->wait();
        }
    }

    public function publish(PublisherInterface $publisher)
    {
        $this->channel->basic_publish($publisher->getMessage()->getMessage(), $publisher->getExchange(), $publisher->getRoutingKey());
    }
}
