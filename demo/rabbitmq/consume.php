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

use Trafficjam\ConsumeMessage;
use Trafficjam\RabbitMq\Connection;
use Trafficjam\RabbitMq\RabbitMqQueue;
use Trafficjam\Trafficjam;

$connection = new Connection('localhost', 5672, 'guest', 'guest');

$queue = new RabbitMqQueue($connection, 'test_queue', true);

$trafficjam = new Trafficjam($queue);

$trafficjam->consume(function (ConsumeMessage $msg) use ($trafficjam) {
    $message = $msg->getMessage();
    echo ' msg: '. $message .PHP_EOL;

    $trafficjam->acknowledge($msg);
});

$queue->disconnect();
