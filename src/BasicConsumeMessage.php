<?php

namespace Trafficjam;

use DateTimeInterface;

final class BasicConsumeMessage implements ConsumeMessage
{
    /**
     * @var string
     */
    private $message;

    /**
     * @var string
     */
    private $id;

    /**
     * @var DateTimeInterface
     */
    private $dateTime;

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
