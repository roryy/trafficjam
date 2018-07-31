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

use Flatfish\Queue\Exception\NoConnectionException;
use Flatfish\Queue\Exception\QueueException;
use Flatfish\Queue\Queue;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class RabbitMqQueue implements Queue, LoggerAwareInterface
{
    /**
     * @var Connection
     */
    protected $connection;

    /**
     * @var string
     */
    protected $routingKey;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $exchange = null;

    /**
     * @var bool
     */
    protected $durable = true;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(
        Connection $connection,
        string $name,
        ?string $exchange = null,
        ?string $routingKey = null,
        bool $durable = true
    ) {
        $this->connection = $connection;
        $this->name = $name;
        $this->exchange = $exchange;

        if (!$routingKey) {
            $this->routingKey = $name;
        }

        $this->durable = $durable;
    }

    /**
     * @param callable $callback
     *
     * @throws QueueException
     */
    public function consume(callable $callback): void
    {
        $this->checkConnection();

        $this->connection->getChannel()->consume($this, $callback);
    }

    /**
     * @param string $message
     *
     * @throws NoConnectionException
     */
    public function publish(string $message): void
    {
        $this->checkConnection();

        $this->connection->getChannel()->publish(new PublishMessage(
            $message,
            $this->exchange,
            $this->routingKey
        ));
    }

    /**
     * @return Connection
     */
    public function getConnection(): Connection
    {
        return $this->connection;
    }

    /**
     * @return string
     */
    public function getRoutingKey(): string
    {
        return $this->routingKey;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getExchange(): string
    {
        return $this->exchange;
    }

    /**
     * @return bool
     */
    public function isDurable(): bool
    {
        return $this->durable;
    }

    public function __destruct()
    {
        $this->connection->disconnect();
    }

    /**
     * @throws NoConnectionException
     */
    private function checkConnection(): void
    {
        if (!$this->connection->isConnected()) {
            $this->getLogger()->error('No connection with RabbitMq', [
                'queueName' => $this->name,
                'exchange' => $this->exchange,
            ]);

            throw new NoConnectionException('No connection with RabbitMQ');
        }
    }

    /**
     * @return LoggerInterface
     */
    public function getLogger(): LoggerInterface
    {
        if (!$this->logger) {
            $this->logger = new NullLogger();
        }

        return $this->logger;
    }

    /**
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }
}
