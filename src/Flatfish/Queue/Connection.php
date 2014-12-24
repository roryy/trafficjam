<?php
/**
 * Created by PhpStorm.
 * User: Rory
 * Date: 26-11-14
 * Time: 22:54
 */

namespace Flatfish\Queue;

use PhpAmqpLib\Connection\AMQPStreamConnection;

class Connection implements ConnectionInterface {

    /**
     * @var AMQPStreamConnection
     */
    protected $connection;

    public function construct($host,$port,$username,$password) {
        $this->connection = new AMQPStreamConnection($host,$port,$username,$password);
    }

    /**
     * @return \PhpAmqpLib\Channel\AMQPChannel
     * @throws \Exception
     */
    public function connect() {
        if(!$this->connection->isConnected()) {
            throw new \Exception('No connection available');
        }

        return $this->connection->channel();
    }

    /**
     * @return \PhpAmqpLib\Channel\AMQPChannel
     */
    public function getChannel() {
        return $this->connection->channel();
    }

    public function isConnected() {
        return $this->connection->isConnected();
    }

    public function disconnect() {
        $this->connection->close();
    }

} 