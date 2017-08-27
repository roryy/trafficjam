<?php

namespace Flatfish\Queue;

interface Consumable
{
    /**
     * @return string
     */
    public function getMessage();

    /**
     * @return void
     */
    public function acknowledge();
}
