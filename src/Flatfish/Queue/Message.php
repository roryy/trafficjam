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

class Message implements MessageInterface
{
    /**
     * @var AMQPMessage
     */
    protected $message;

    public function __construct($message)
    {
        $this->message = new AMQPMessage($message);
    }

    /**
     * @return AMQPMessage
     */
    public function getMessage()
    {
        return $this->message;
    }
}
