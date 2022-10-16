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

interface QueueInterface
{
    public function publish(string $message): void;

    public function consume(callable $callback): void;

    public function acknowledge(ConsumableMessageInterface $consumeMessage): void;

    /**
     * @throws QueueIsEmptyException
     */
    public function pop(): ConsumableMessageInterface;
}
