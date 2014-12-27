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

use Flatfish\Queue\Exception\QueueException;

class Queue extends QueueAbstract {

    protected $callback;

    public function __construct(ConnectionInterface $connection,$name,$exchange = null,$routingkey = null, $durable = true) {
        $this->name = $name;

        if(!$routingkey) {
            $this->routingKey = $name;
        }

        $this->exchange = $exchange;
        $this->durable = $durable;

        $this->setConnection($connection);
    }

    public function consume() {
        if(!is_callable($this->callback)) {
            throw new QueueException('Please specify a callable callback');
        }

        $this->connection->getChannel()->consume($this,$this->callback);
    }

    public function setCallback($callback) {
        $this->callback = $callback;

        return $this;
    }

    public function __destruct() {
        $this->connection->disconnect();
    }
}