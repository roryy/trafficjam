# Flatfishqueue

[![Build Status](https://travis-ci.org/roryy/flatfishqueue.svg?branch=master)](https://travis-ci.org/roryy/flatfishqueue)

PHP queue library for RabbitMQ. It's possible to add more queue types like SQS

Please do not use in production as BC breaks can occur before reaching version 1.0.

## Installation
~~~
composer require roryy/flatfishqueue
~~~

## Usage
To use flatfish queue in combination with rabbitmq, first you must setup your rabbitmq credentials

Like this:
~~~
use FlatfishQueue\Infrastructure\RabbitMq\Connection;
use FlatfishQueue\Infrastructure\RabbitMq\RabbitMqQueue;

$connection = new Connection('localhost', 5672, 'guest', 'guest');
~~~

Then create a queue like this:
~~~
$queue = new RabbitMqQueue($connection, $queueName, $exchange, $routingKey);
~~~

To publish one or more messages to your queue:
~~~
$queue->publish('test 1');
$queue->publish('test 2');
~~~

And consume (with a callback), don't forget to acknowledge
~~~
$queue->consume(function (Consumable $msg) {
    $message = $msg->getMessage();
    echo ' msg: '. $message .PHP_EOL;

    $msg->acknowledge();
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
If you want to use another queuing system besides RabbitMQ then please implement the Queue interface when creating your own. Please submit a pull request.

## License
FlatfishQueue is licensed under the MIT License. See the bundled LICENSE file for details.