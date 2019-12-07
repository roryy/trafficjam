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

use FlatfishQueue\NoConnectionException;
use FlatfishQueue\Queue;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

final class RabbitMqQueue implements Queue, LoggerAwareInterface
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $routingKey;

    /**
     * @var bool
     */
    private $durable;

    /**
     * @var string
     */
    private $exchange = null;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(
        Connection $connection,
        string $name,
        bool $durable = true
    ) {
        $this->connection = $connection;
        $this->name = $name;
        $this->routingKey = $name;

        $this->durable = $durable;
        $this->logger = new NullLogger();
    }

    /**
     * @param callable $callback
     *
     * @throws NoConnectionException
     */
    public function consume(callable $callback): void
    {
        $this->checkConnection();

        $this->connection->getChannel()->consume($this->getName(), $callback);
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
            $this->routingKey,
            $this->exchange
        ));
    }

    public function withExchange(string $exchange): void
    {
        $this->exchange = $exchange;
    }

    public function withRoutingKey(string $routingKey): void
    {
        $this->routingKey = $routingKey;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @throws NoConnectionException
     */
    private function checkConnection(): void
    {
        if (!$this->connection->isConnected()) {
            $this->logger->error('No connection with RabbitMq', [
                'queueName' => $this->name,
                'exchange' => $this->exchange,
            ]);

            throw new NoConnectionException('No connection with RabbitMQ');
        }
    }

    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }

    public function disconnect(): void
    {
        $this->connection->disconnect();
    }
}
