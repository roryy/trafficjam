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

namespace FlatfishQueue\Infrastructure\RabbitMq;

class PublishMessage
{
    /**
     * @var string
     */
    private $message;

    /**
     * @var string
     */
    private $routingKey;

    /**
     * @var string
     */
    private $exchange;

    public function __construct(string $message, string $routingKey, ?string $exchange)
    {
        $this->message = $message;
        $this->routingKey = $routingKey;
        $this->exchange = $exchange;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getRoutingKey(): string
    {
        return $this->routingKey;
    }

    public function getExchange(): string
    {
        return $this->exchange;
    }
}
