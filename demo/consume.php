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

$connection = new Connection('localhost',5672,'guest','guest');

$queue = new Queue($connection,'testqueue','Flatfish','testqueue');
$queue->setCallback(function($msg) use($queue) {
    echo ' msg: '.$msg->body.PHP_EOL;
    $queue->getConnection()->getChannel()->acknowledge($msg);
})->consume();