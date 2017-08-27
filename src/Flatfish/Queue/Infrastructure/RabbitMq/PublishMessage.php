<?php
/**
 * Flatfish Queue
 *
 * @author Rory Scholman <rory@roryy.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Flatfish\Queue\Infrastructure\RabbitMq;

class PublishMessage
{
    /**
     * @var string
     */
    private $message;

    /**
     * @var string
     */
    private $exchange;

    /**
     * @var string
     */
    private $routingKey;

    /**
     * @param string $message
     * @param string $exchange
     * @param string $routingKey
     */
    public function __construct($message, $exchange, $routingKey)
    {
        $this->message = $message;
        $this->exchange = $exchange;
        $this->routingKey = $routingKey;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return string
     */
    public function getExchange()
    {
        return $this->exchange;
    }

    /**
     * @return string
     */
    public function getRoutingKey()
    {
        return $this->routingKey;
    }
}
