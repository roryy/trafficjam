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

interface ConnectionInterface
{
    public function connect();

    /**
     * @return ChannelInterface
     */
    public function getChannel();

    public function isConnected();

    public function disconnect();
}
