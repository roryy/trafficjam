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

interface PublisherInterface
{
    public function publish($message);

    /**
     * @return MessageInterface
     */
    public function getMessage();

    public function getName();

    public function getExchange();

    public function getRoutingKey();

    public function getDurable();
}
