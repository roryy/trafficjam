<?php
/**
 * Created by PhpStorm.
 * User: Rory
 * Date: 20-12-14
 * Time: 20:05
 */

namespace Flatfish\Queue;


interface PublisherInterface {

    public function publish($message);

} 