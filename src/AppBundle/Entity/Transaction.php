<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="transactions")
 */
class Transaction implements \JsonSerializable {

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var int
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="LaserOperation", mappedBy="transaction")
     */
    private $laserOperations;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @var int|null
     */
    private $userId = null;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @var int|null
     */
    private $userTransactionId = null;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @var int|null
     */
    private $operatorId = null;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @var int|null
     */
    private $operatorTransactionId = null;

    /**
     * @ORM\Column(type="integer")
     * @var int
     */
    private $duration;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @var string|null
     */
    private $comment = null;

    /**
     * @ORM\Column(type="string", length=16)
     * @var string
     */
    private $mode;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created", type="datetime")
     * @var \DateTime
     */
    private $created;

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
     * @return mixed
     */
    function getLaserOperations() {
        return $this->laserOperations;
    }

    /**
     * @param mixed $laserOperations
     * @return Transaction
     */
    function setLaserOperations($laserOperations) {
        $this->laserOperations = $laserOperations;

        return $this;
    }

    /**
     * @return int|null
     */
    function getUserId() {
        return $this->userId;
    }

    /**
     * @param int|null $userId
     * @return Transaction
     */
    function setUserId($userId) {
        $this->userId = $userId;

        return $this;
    }

    /**
     * @return int|null
     */
    function getUserTransactionId() {
        return $this->userTransactionId;
    }

    /**
     * @param int|null $userTransactionId
     * @return Transaction
     */
    function setUserTransactionId($userTransactionId) {
        $this->userTransactionId = $userTransactionId;

        return $this;
    }

    /**
     * @return int|null
     */
    function getOperatorId() {
        return $this->operatorId;
    }

    /**
     * @param int|null $operatorId
     * @return Transaction
     */
    function setOperatorId($operatorId) {
        $this->operatorId = $operatorId;

        return $this;
    }

    /**
     * @return int|null
     */
    function getOperatorTransactionId() {
        return $this->operatorTransactionId;
    }

    /**
     * @param int|null $operatorTransactionId
     * @return Transaction
     */
    function setOperatorTransactionId($operatorTransactionId) {
        $this->operatorTransactionId = $operatorTransactionId;

        return $this;
    }

    /**
     * @return int
     */
    function getDuration() {
        return $this->duration;
    }

    /**
     * @param int $duration
     * @return Transaction
     */
    function setDuration($duration) {
        $this->duration = $duration;

        return $this;
    }

    /**
     * @return null|string
     */
    function getComment() {
        return $this->comment;
    }

    /**
     * @param null|string $comment
     * @return Transaction
     */
    function setComment($comment) {
        $this->comment = $comment;

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

    /**
     * @return string
     */
    function getMode() {
        return $this->mode;
    }

    /**
     * @param string $mode
     */
    function setMode($mode) {
        $this->mode = $mode;
    }

    /**
     * @return array
     */
    function jsonSerialize() {
        return [
            'id' => $this->id,
            'userId' => $this->userId,
            'userTransactionId' => $this->userTransactionId,
            'operatorId' => $this->operatorId,
            'operatorTransactionId' => $this->operatorTransactionId,
            'comment' => $this->comment,
            'duration' => $this->duration,
            'created' => $this->created
        ];
    }
}
