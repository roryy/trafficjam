<?php
/**
 * Flatfish Queue
 *
 * @author Rory Scholman <rory@roryy.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
require_once '../vendor/autoload.php';

use Flatfish\Queue\Connection;
use Flatfish\Queue\Queue;

$connection = new Connection('localhost', 5672, 'guest', 'guest');

$queue = new Queue($connection, 'testqueue', 'Flatfish');

for ($i = 1; $i < 10; $i++) {
    $queue->publish('test '.$i);
}
