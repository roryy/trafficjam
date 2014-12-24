<?php
/**
 * Created by PhpStorm.
 * User: Rory
 * Date: 21-12-14
 * Time: 19:03
 */

namespace Flatfish\Queue;


interface ConnectionInterface {

    public function connect();

    public function getChannel();

    public function isConnected();

    public function disconnect();

} 