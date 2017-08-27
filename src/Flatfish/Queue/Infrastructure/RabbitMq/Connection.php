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

use Flatfish\Queue\Infrastructure\RabbitMq\Channel;
use Flatfish\Queue\Infrastructure\RabbitMq\ChannelInterface;
use Flatfish\Queue\ConnectionInterface;
use Flatfish\Queue\Exception\NoConnectionException;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class Connection implements ConnectionInterface
{
    /**
     * @var AMQPStreamConnection
     */
    protected $connection;

    /**
     * @var ChannelInterface
     */
    protected $channel;

    public function __construct($host, $port, $username, $password)
    {
        $this->connection = new AMQPStreamConnection($host, $port, $username, $password);

        $this->channel = new Channel($this, $this->connection->channel());
    }

    /**
     * @return Connection
     *
     * @throws \Exception
     */
    public function connect()
    {
        $this->connection->reconnect();
    }

    /**
     * @return ChannelInterface
     */
    public function getChannel()
    {
        return $this->channel;
    }

    public function setChannel(ChannelInterface $channel)
    {
        $this->channel = $channel;

        return $this;
    }

    public function isConnected()
    {
        return $this->connection->isConnected();
    }

    public function disconnect()
    {
        $this->connection->close();
    }
}
