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

use PhpAmqpLib\Connection\AMQPStreamConnection;

class Connection
{
    /**
     * @var AMQPStreamConnection
     */
    private $connection;

    /**
     * @var Channel
     */
    private $channel;

    public function __construct(string $host, int $port, string $username, string $password)
    {
        $this->connection = new AMQPStreamConnection($host, $port, $username, $password);

        $this->channel = new Channel($this->connection->channel());
    }

    public function connect(): void
    {
        $this->connection->reconnect();
    }

    public function getChannel(): Channel
    {
        return $this->channel;
    }

    public function isConnected(): bool
    {
        return $this->connection->isConnected();
    }

    public function disconnect(): void
    {
        $this->channel->disconnect();
        $this->connection->close();
    }
}
