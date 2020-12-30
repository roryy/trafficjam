# Traffic jam

PHP queue library for RabbitMQ. It's possible to add more queue types like SQS

Please do not use in production as BC breaks can occur before reaching version 1.0.

## Installation
~~~
composer require roryy/trafficjam "0.5.*"
~~~

## Usage
To use the Trafficjam library in combination with Rabbitmq, you have to setup your Rabbitmq credentials first.

Like this:
~~~
use Trafficjam\Infrastructure\RabbitMq\Connection;
use Trafficjam\Infrastructure\RabbitMq\RabbitMqQueue;

$connection = new Connection('localhost', 5672, 'guest', 'guest');
~~~

Then create a queue like this:
~~~
$queue = new RabbitMqQueue($connection, $queueName, $durable, $exchange, $routingKey);
$trafficjam = new Trafficjam($queue);
~~~

To publish one or more messages to your queue:
~~~
$trafficjam->publish('test 1');
$trafficjam->publish('test 2');
~~~

And consume (with a callback), don't forget to acknowledge
~~~
$trafficjam->consume(function (Consumable $msg) use ($trafficjam) {
    $message = $msg->getMessage();
    echo ' msg: '. $message .PHP_EOL;

    $trafficjam->acknowledge($msg);
});
~~~

This will output:
~~~
test 1
test 2
~~~

## Future
In the future this library will also going to support SQS.

## Contribute
If you want to use another queuing system besides RabbitMQ then please implement the Queue interface when creating your own. Please create a pull request.

## License
Trafficjam is licensed under the MIT License. See the bundled LICENSE file for details.
