<?php

namespace AppBundle\Utils\Strichliste;

class User {

    /**
     * @var $id
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var float
     */
    private $balance;

    /**
     * @param int $id
     */
    function __construct($id) {
        $this->id = $id;
    }

    /**
     * @param array $response
     * @return static
     */
    static function byResponse(array $response) {
        $user = new static($response['id']);
        $user->setName($response['name']);
        $user->setBalance($response['balance']);

        return $user;
    }

    /**
     * @return mixed
     */
    function getId() {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    function setId($id) {
        $this->id = $id;
    }

    /**
     * @return string
     */
    function getName() {
        return $this->name;
    }

    /**
     * @param string $name
     */
    function setName($name) {
        $this->name = $name;
    }

    /**
     * @return float
     */
    function getBalance() {
        return $this->balance;
    }

    /**
     * @param float $balance
     */
    function setBalance($balance) {
        $this->balance = $balance;
    }

}