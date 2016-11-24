<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="laseroperations")
 */
class LaserOperation implements \JsonSerializable {

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var int
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Transaction", inversedBy="laserOperations")
     * @ORM\JoinColumn(name="transactionId", referencedColumnName="id")
     */
    private $transaction = null;

    /**
     * @ORM\Column(type="integer")
     * @var int
     */
    private $duration;

    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updated", type="datetime", nullable=true)
     * @var \DateTime
     */
    private $updated = null;

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
     * @return LaserOperation
     */
    function setId($id) {
        $this->id = $id;

        return $this;
    }

    /**
     * @return mixed
     */
    function getTransaction() {
        return $this->transaction;
    }

    /**
     * @param mixed $transaction
     * @return LaserOperation
     */
    function setTransaction($transaction) {
        $this->transaction = $transaction;

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
     * @return LaserOperation
     */
    function setDuration($duration) {
        $this->duration = $duration;

        return $this;
    }

    /**
     * @return \DateTime
     */
    function getUpdated() {
        return $this->updated;
    }

    /**
     * @param \DateTime $updated
     * @return LaserOperation
     */
    function setUpdated($updated) {
        $this->updated = $updated;

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
     * @return LaserOperation
     */
    function setCreated($created) {
        $this->created = $created;

        return $this;
    }

    /**
     * @return array
     */
    function jsonSerialize() {
        return [
            'id' => $this->id,
            'transaction' => $this->transaction,
            'duration' => $this->duration,
            'updated' => $this->updated,
            'created' => $this->created
        ];
    }
}
