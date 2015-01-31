<?php
/**
 * Flatfish Queue
 *
 * @author Rory Scholman <rory@roryy.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Flatfish\Queue;

use PhpAmqpLib\Message\AMQPMessage;

interface ChannelInterface
{

    public function acknowledge(AMQPMessage $msg);
    public function consume(ConsumerInterface $consumer, $callback);
    public function publish(PublisherInterface $publisher);
}
