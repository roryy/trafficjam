<?php
/**
 * Created by PhpStorm.
 * User: Rory
 * Date: 25-11-14
 * Time: 22:57
 */

namespace Flatfish\Queue;


interface ConsumerInterface {

    public function setValues();
    public function consume();
    public function setName($name);

} 