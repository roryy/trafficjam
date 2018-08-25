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

interface Queue
{
    /**
     * @param string $message
     */
    public function publish(string $message): void;

    /**
     * @param callable $callback
     */
    public function consume(callable $callback): void;
}
