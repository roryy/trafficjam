<?php
/**
 * Traffic jam
 *
 * @author Rory Scholman <rory@roryy.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
require_once __DIR__ . '/../../vendor/autoload.php';

use Trafficjam\RabbitMq\Connection;
use Trafficjam\RabbitMq\RabbitMqQueue;
use Trafficjam\Trafficjam;

$connection = new Connection('rabbitmq', 5672, 'guest', 'guest');

$queue = new RabbitMqQueue($connection, 'test_queue', true);

$trafficjam = new Trafficjam($queue);

for ($i = 1; $i < 10; $i++) {
    $queue->publish('test '.$i);
}

$queue->disconnect();
