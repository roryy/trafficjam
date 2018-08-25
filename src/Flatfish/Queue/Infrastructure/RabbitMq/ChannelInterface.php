<?php
/**
 * Flatfish Queue
 *
 * @author Rory Scholman <rory@roryy.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Flatfish\Queue\Infrastructure\RabbitMq;

use PhpAmqpLib\Message\AMQPMessage;

interface ChannelInterface
{
    public function acknowledge(AMQPMessage $msg): void;
    public function consume(RabbitMqQueue $consumer, callable $callback): void;
    public function publish(PublishMessage $publisher): void;
}
