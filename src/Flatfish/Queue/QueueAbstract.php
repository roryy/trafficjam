<?php
/**
 * Created by PhpStorm.
 * User: Rory
 * Date: 30-11-14
 * Time: 17:27
 */

namespace Flatfish\Queue;

use PhpAmqpLib\Message\AMQPMessage;

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

        }

        $msg = new AMQPMessage($message);
        $this->connection->getChannel()->basic_publish($msg);

        return true;
    }

    abstract function consume();

    /**
     * @param ConnectionInterface $connection
     */
    public function setConnection(ConnectionInterface $connection) {
        $this->connection = $connection;
    }

    /**
     * @return ConnectionInterface
     */
    public function getConnection() {
        return $this->connection;
    }

} 