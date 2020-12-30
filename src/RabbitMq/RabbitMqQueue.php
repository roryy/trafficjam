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

use Trafficjam\ConsumeMessage;
use Trafficjam\NoConnectionException;
use Trafficjam\Queue;
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
     * @var string|null
     */
    private $routingKey;

    /**
     * @var bool
     */
    private $durable;

    /**
     * @var string|null
     */
    private $exchange;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(
        Connection $connection,
        string $name,
        bool $durable = true,
        ?string $exchange = null,
        ?string $routingKey = null
    ) {
        $this->connection = $connection;
        $this->name = $name;
        $this->routingKey = $name;
        $this->durable = $durable;
        $this->logger = new NullLogger();
        $this->exchange = $exchange;
        $this->routingKey = $routingKey;
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

    public function acknowledge(ConsumeMessage $consumeMessage): void
    {
        $this->connection->getChannel()->acknowledge((int) $consumeMessage->getId());
    }

    public function pop(): ConsumeMessage
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

    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }

    public function disconnect(): void
    {
        $this->connection->disconnect();
    }
}
