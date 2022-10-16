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

namespace Trafficjam;

final class BasicConsumableMessage implements ConsumableMessageInterface
{
    private string $message;

    private string $id;

    public function __construct(string $message, string $id)
    {
        $this->message = $message;
        $this->id = $id;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getId(): string
    {
        return $this->id;
    }
}
