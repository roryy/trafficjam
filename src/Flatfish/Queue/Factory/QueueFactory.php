<?php
/**
 * Created by PhpStorm.
 * User: rory
 * Date: 09-12-14
 * Time: 22:29
 */

namespace Flatfish\Queue\Factory;


use Flatfish\Queue\Connection;
use Flatfish\Queue\Queue;

class QueueFactory {

    /**
     * @param $host
     * @param $port
     * @param $username
     * @param $password
     * @param $name
     * @param null $routingKey
     * @param bool $durable
     * @return QueueInterface
     */
    public static function createQueue($host, $port, $username, $password, $name, $exchange = null, $routingKey = null, $durable = true) {
        $connection = new Connection($host,$port,$username,$password);

        $queue = new Queue($connection,$name,$exchange,$routingKey,$durable);

        return $queue;
    }

} 