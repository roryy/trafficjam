<?php
/**
 * Traffic jam
 *
 * @author Rory Scholman <rory@roryy.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Trafficjam\RabbitMq;

class PublishMessage
{
    private string $message;

    private ?string $routingKey;

    private ?string $exchange;

    public function __construct(string $message, ?string $routingKey = null, ?string $exchange = null)
    {
        $this->message = $message;
        $this->routingKey = $routingKey;
        $this->exchange = $exchange;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getRoutingKey(): ?string
    {
        return $this->routingKey;
    }

    public function getExchange(): ?string
    {
        return $this->exchange;
    }
}
