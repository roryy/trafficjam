<?php
/**
 * Created by PhpStorm.
 * User: Rory
 * Date: 25-11-14
 * Time: 22:32
 */

require_once '../vendor/autoload.php';
use \PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPStreamConnection('home.flatfish.nl', 5672, 'guest', 'guest');
$channel = $connection->channnel();