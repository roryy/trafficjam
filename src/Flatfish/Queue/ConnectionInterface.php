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

use Flatfish\Queue\Infrastructure\RabbitMq\ChannelInterface;

interface ConnectionInterface
{
    /**
     * @return void
     */
    public function connect();

    /**
     * @return ChannelInterface
     */
    public function getChannel();

    /**
     * @return bool
     */
    public function isConnected();

    /**
     * @return void
     */
    public function disconnect();
}
