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

use FlatfishQueue\Infrastructure\RabbitMq\Connection;
use FlatfishQueue\Infrastructure\RabbitMq\RabbitMqQueue;

$connection = new Connection('localhost', 5672, 'guest', 'guest');

$queue = new RabbitMqQueue($connection, 'test_queue', 'flatfish');

for ($i = 1; $i < 10; $i++) {
    $queue->publish('test '.$i);
}

$queue->disconnect();