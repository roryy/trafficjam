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
use Trafficjam\ConsumeMessage;
use PhpAmqpLib\Message\AMQPMessage;

final class Message implements ConsumeMessage
{
    /**
     * @var AMQPMessage
     */
    private $message;

    /**
     * @var Channel
     */
    private $channel;

    public function __construct(AMQPMessage $message, Channel $channel)
    {
        $this->message = $message;
        $this->channel = $channel;
    }

    public function getMessage(): string
    {
        return $this->message->getBody();
    }

    public function getDateTime(): DateTimeInterface
    {
        // TODO: Implement getDateTime() method.
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
