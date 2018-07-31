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

namespace Flatfish\Queue;

use Flatfish\Queue\Infrastructure\RabbitMq\ChannelInterface;

interface ConnectionInterface
{
    /**
     * @return void
     */
    public function connect(): void;

    /**
     * @return ChannelInterface
     */
    public function getChannel(): ChannelInterface;

    /**
     * @return bool
     */
    public function isConnected(): bool;

    /**
     * @return void
     */
    public function disconnect(): void;
}
