<?php
/**
 * Flatfish Queue
 *
 * @author Rory Scholman <rory@roryy.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
require_once __DIR__ . '/../../vendor/autoload.php';

use Flatfish\Queue\Consumable;
use Flatfish\Queue\Infrastructure\RabbitMq\Connection;
use Flatfish\Queue\Infrastructure\RabbitMq\RabbitMqQueue;

$connection = new Connection('localhost', 5672, 'guest', 'guest');

$queue = new RabbitMqQueue($connection, 'test_queue', 'flatfish', 'testqueue');

$queue->consume(function (Consumable $msg) use ($queue) {
    $message = $msg->getMessage();
    echo ' msg: '. $message .PHP_EOL;

    $msg->acknowledge();
});
