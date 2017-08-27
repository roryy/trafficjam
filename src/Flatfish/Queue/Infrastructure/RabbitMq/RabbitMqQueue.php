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

use Flatfish\Queue\Exception\ConsumerNotCallableException;
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

    /**
     * @param Connection $connection
     * @param string     $name
     * @param string     $exchange
     * @param string     $routingKey
     * @param bool       $durable
     */
    public function __construct(Connection $connection, $name, $exchange = null, $routingKey = null, $durable = true)
    {
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
    public function consume($callback)
    {
        $this->checkConnection();

        if (!is_callable($callback)) {
            $this->getLogger()->error('Callback is not callable', [
                'queueName' => $this->name,
                'exchange' => $this->exchange,
            ]);

            throw new ConsumerNotCallableException('Please specify a callable callback');
        }

        $this->connection->getChannel()->consume($this, $callback);
    }

    /**
     * @param string $message
     *
     * @throws NoConnectionException
     */
    public function publish($message)
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
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * @return string
     */
    public function getRoutingKey()
    {
        return $this->routingKey;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getExchange()
    {
        return $this->exchange;
    }

    /**
     * @return bool
     */
    public function isDurable()
    {
        return $this->durable;
    }

    public function __destruct()
    {
        $this->connection->disconnect();
    }

    private function checkConnection()
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
    public function getLogger()
    {
        if (!$this->logger) {
            $this->logger = new NullLogger();
        }

        return $this->logger;
    }

    /**
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
}
