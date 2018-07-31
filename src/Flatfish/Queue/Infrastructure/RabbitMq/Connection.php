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

    public function __construct(string $host, int $port, string $username, string $password)
    {
        $this->connection = new AMQPStreamConnection($host, $port, $username, $password);

        $this->channel = new Channel($this, $this->connection->channel());
    }

    /**
     * @throws \Exception
     */
    public function connect(): void
    {
        $this->connection->reconnect();
    }

    /**
     * @return ChannelInterface
     */
    public function getChannel(): ChannelInterface
    {
        return $this->channel;
    }

    public function setChannel(ChannelInterface $channel): void
    {
        $this->channel = $channel;
    }

    public function isConnected(): bool
    {
        return $this->connection->isConnected();
    }

    public function disconnect(): void
    {
        $this->connection->close();
    }
}
