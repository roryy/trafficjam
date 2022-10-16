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

use DateTimeInterface;
use Trafficjam\ConsumableMessageInterface;
use PhpAmqpLib\Message\AMQPMessage;

final class Message implements ConsumableMessageInterface
{
    /**
     * @var AMQPMessage
     */
    private AMQPMessage $message;

    private Channel $channel;

    public function __construct(AMQPMessage $message, Channel $channel)
    {
        $this->message = $message;
        $this->channel = $channel;
    }

    public function getMessage(): string
    {
        return $this->message->getBody();
    }

    public function getId(): string
    {
        return (string) $this->message->getDeliveryTag();
    }

    public function acknowledge(): void
    {
        $this->channel->acknowledge($this->message);
    }
}
