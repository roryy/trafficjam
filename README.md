flatfishqueue
=============

PHP queue library for RabbitMQ

#Usage
A little bit of RabbitMQ knowledge is required.
Use composer to install dependencies

First setup your rabbitmq credentials
~~~
use Flatfish\Queue\Connection;
use Flatfish\Queue\Queue;

$connection = new Connection('localhost', 5672, 'guest', 'guest');
~~~

Then make a queue
~~~
$queue = new Queue($connection,$queueName,$exchangeName,$routingKey);
~~~

Or use the included factory
~~~
use Flatfish\Queue\Factory\QueueFactory

$queue = QueueFactory::createQueue('localhost', 5672, 'guest', 'guest',$queueName,$exchangeName,$routingKey);
~~~

Publish to the queue
~~~
$queue->publish('test');
~~~

And consume (with a callback), don't forget to acknowledge
~~~
$queue->setCallback(function($msg) use($queue) {
    echo ' msg: '.$msg->body.PHP_EOL;
    $queue->getConnection()->getChannel()->acknowledge($msg);
})->consume();
~~~