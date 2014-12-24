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

use PhpAmqpLib\Channel\AMQPChannel;

class Channel implements ChannelInterface {

    /**
     * @var ConnectionInterface
     */
    protected $connection;

    /**
     * @var AMQPChannel
     */
    protected $channel;

    public function __construct(ConnectionInterface $connection, AMQPChannel $channel) {
        $this->connection = $connection;
        $this->channel = $channel;
    }

    public function getConnection() {
        return $this->connection;
    }

    public function acknowledge($tag) {
        $this->channel->basic_ack($tag);
    }

    public function consume($queue,$callback) {
        $this->channel->basic_consume($queue,'',false,false,false,false,$callback);
    }

    public function publish(MessageInterface $message) {
        $this->channel->basic_publish($message->getMessage());
    }

} 