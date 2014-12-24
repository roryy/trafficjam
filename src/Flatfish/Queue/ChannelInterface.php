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

interface ChannelInterface {

    public function acknowledge($tag);
    public function consume($queue,$callback);
    public function publish(MessageInterface $message);

} 