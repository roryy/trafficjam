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

namespace FlatfishQueue;

interface Consumable
{
    /**
     * @return string
     */
    public function getMessage(): string;

    /**
     * @return void
     */
    public function acknowledge(): void;
}
