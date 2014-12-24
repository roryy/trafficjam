<?php
/**
 * Flatfish Queue
 *
 * @author Rory Scholman <rory@roryy.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Flatfish\Queue\Factory;

use Flatfish\Queue\Connection;
use Flatfish\Queue\Queue;
use Flatfish\Queue\QueueAbstract;

class QueueFactory {

    /**
     * @param $host
     * @param $port
     * @param $username
     * @param $password
     * @param $name
     * @param null $exchange
     * @param null $routingKey
     * @param bool $durable
     * @return QueueAbstract
     */
    public static function createQueue($host, $port, $username, $password, $name, $exchange = null, $routingKey = null, $durable = true) {
        $connection = new Connection($host,$port,$username,$password);

        $queue = new Queue($connection,$name,$exchange,$routingKey,$durable);

        return $queue;
    }

} 