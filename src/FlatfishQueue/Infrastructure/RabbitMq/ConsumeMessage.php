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

use FlatfishQueue\Consumable;
use PhpAmqpLib\Message\AMQPMessage;

final class ConsumeMessage implements Consumable
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

    public function acknowledge(): void
    {
        $this->channel->acknowledge($this->message);
    }
}
