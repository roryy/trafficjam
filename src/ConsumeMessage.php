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

use DateTimeInterface;

interface ConsumeMessage
{
    public function getMessage(): string;

    public function getId(): string;
}
