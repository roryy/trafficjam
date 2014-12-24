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

use Flatfish\Queue\Exception\NoConnectionException;

abstract class QueueAbstract implements ConsumerInterface,PublisherInterface {

    /**
     * @var ConnectionInterface
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

    public function publish($message) {
        if(!$this->connection->isConnected()) {
            throw new NoConnectionException('No connection with RabbitMQ');
        }

        $msg = $this->processMessage($message);

        $this->connection->getChannel()->publish($msg);

        return true;
    }

    /**
     * @param $message
     * @return MessageInterface
     */
    protected function processMessage($message) {
        if($message instanceof MessageInterface) {
            return $message;
        }

        return new Message($message);
    }

    abstract function consume();

    /**
     * @param ConnectionInterface $connection
     * @return QueueAbstract
     */
    public function setConnection(ConnectionInterface $connection) {
        $this->connection = $connection;
        return $this;
    }

    protected function acknowledge($tag) {
        $this->connection->getChannel()->acknowledge($tag);
    }

    /**
     * @return ConnectionInterface
     */
    public function getConnection() {
        return $this->connection;
    }

} 