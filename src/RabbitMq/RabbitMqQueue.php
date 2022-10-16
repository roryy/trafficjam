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

use Trafficjam\ConsumableMessageInterface;
use Trafficjam\NoConnectionException;
use Trafficjam\QueueInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

final class RabbitMqQueue implements QueueInterface
{
    private Connection $connection;

    private string $name;

    private ?string $routingKey;

    private bool $durable;

    private ?string $exchange;

    private LoggerInterface $logger;

    public function __construct(
        Connection $connection,
        string $name,
        bool $durable = true,
        ?string $exchange = null,
        ?string $routingKey = null,
        ?LoggerInterface $logger = null
    ) {
        $this->connection = $connection;
        $this->name = $name;
        $this->durable = $durable;
        $this->exchange = $exchange;
        $this->routingKey = $routingKey;
        $this->logger = $logger ?? new NullLogger();
    }

    /**
     * @throws NoConnectionException
     */
    public function publish(string $message): void
    {
        $this->checkConnection();

        $this->connection->getChannel()->declareQueue($this->getName(), $this->durable);
        $this->connection->getChannel()->publish(new PublishMessage(
            $message,
            $this->routingKey ?? $this->name,
            $this->exchange
        ));
    }

    /**
     * @throws NoConnectionException
     */
    public function consume(callable $callback): void
    {
        $this->checkConnection();

        $this->connection->getChannel()->declareQueue($this->getName(), $this->durable);
        $this->connection->getChannel()->consume($this->getName(), $callback);
    }

    public function acknowledge(ConsumableMessageInterface $consumeMessage): void
    {
        $this->connection->getChannel()->acknowledge((int) $consumeMessage->getId());
    }

    public function pop(): ConsumableMessageInterface
    {
        return $this->connection->getChannel()->pop($this->getName());
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

    public function disconnect(): void
    {
        $this->connection->disconnect();
    }
}
