<?php

namespace AppBundle\Utils\Strichliste;

class Transaction {

    /**
     * @var int $id
     */
    private $id;

    /**
     * @var int $userId
     */
    private $userId;

    /**
     * @var float $value
     */
    private $value;

    /**
     * @var \DateTime $created
     */

    private $created;

    /**
     * @param int $id
     */
    function __construct($id) {
        $this->id = $id;
    }

    /*
     * @param array $response
     * @return static
     */
    static function byResponse(array $response) {
        $transaction = new static($response['id']);
        $transaction
            ->setUserId($response['userId'])
            ->setValue($response['value'])
            ->setCreated(new \DateTime($response['createDate']));

        return $transaction;
    }

    /**
     * @return int
     */
    function getId() {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Transaction
     */
    function setId($id) {
        $this->id = $id;

        return $this;
    }

    /**
     * @return int
     */
    function getUserId() {
        return $this->userId;
    }

    /**
     * @param int $userId
     * @return Transaction
     */
    function setUserId($userId) {
        $this->userId = $userId;

        return $this;
    }

    /**
     * @return float
     */
    function getValue() {
        return $this->value;
    }

    /**
     * @param float $value
     * @return Transaction
     */
    function setValue($value) {
        $this->value = $value;

        return $this;
    }

    /**
     * @return \DateTime
     */
    function getCreated() {
        return $this->created;
    }

    /**
     * @param \DateTime $created
     * @return Transaction
     */
    function setCreated($created) {
        $this->created = $created;

        return $this;
    }

}