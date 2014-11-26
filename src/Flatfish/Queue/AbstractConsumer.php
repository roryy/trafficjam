<?php
/**
 * Created by PhpStorm.
 * User: Rory
 * Date: 26-11-14
 * Time: 22:48
 */

namespace Flatfish\Queue;


abstract class AbstractConsumer implements ConsumerInterface {

    protected $name;

    /**
     * @var Connection
     */
    protected $connection;

    public function __construct(Connection $connection) {
        $this->connection = $connection;

        $this->init();
    }

    public function init() {

    }

    abstract function setValues();

    public function setName($name) {
        $this->name = $name;
    }

    abstract function consume();
}