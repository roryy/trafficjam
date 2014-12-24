<?php
/**
 * Created by PhpStorm.
 * User: Rory
 * Date: 30-11-14
 * Time: 15:22
 */

namespace Flatfish\Queue;

class Queue extends QueueAbstract {

    protected $callback;


    public function __construct(ConnectionInterface $connection,$name,$exchange = null,$routingkey = null, $durable = true) {
        $this->name = $name;

        if(!$routingkey) {
            $this->routingKey = $name;
        }

        $this->setConnection($connection);
    }

    public function consume() {
        if(!is_callable($this->callback)) {
            throw new \Exception('Please specify a callable callback');
        }

        $this->connection->getChannel()->basic_consume();
    }

    public function setCallback($callback) {
        $this->callback = $callback;

        return $this;
    }

    public function __destruct() {
        $this->connection->disconnect();

    }



} 