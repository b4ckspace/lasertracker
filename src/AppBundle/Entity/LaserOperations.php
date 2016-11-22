<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="laseroperations")
 */
class LaserOperations implements \JsonSerializable {

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @var int|null
     */
    private $userId = null;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @var int|null
     */
    private $operatorId = null;

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
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     *
     * @return Laseroperations
     */
    public function setUserId($userId) {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return integer
     */
    public function getUserId() {
        return $this->userId;
    }

    /**
     * Set operatorId
     *
     * @param integer $operatorId
     *
     * @return Laseroperations
     */
    public function setOperatorId($operatorId) {
        $this->operatorId = $operatorId;

        return $this;
    }

    /**
     * Get operatorId
     *
     * @return integer
     */
    public function getOperatorId() {
        return $this->operatorId;
    }

    /**
     * Set duration
     *
     * @param integer $duration
     *
     * @return Laseroperations
     */
    public function setDuration($duration) {
        $this->duration = $duration;

        return $this;
    }

    /**
     * Get duration
     *
     * @return integer
     */
    public function getDuration() {
        return $this->duration;
    }

    /**
     * Set comment
     *
     * @param string $comment
     *
     * @return Laseroperations
     */
    public function setComment($comment) {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string
     */
    public function getComment() {
        return $this->comment;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     *
     * @return Laseroperations
     */
    public function setUpdated($updated) {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime
     */
    public function getUpdated() {
        return $this->updated;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Laseroperations
     */
    public function setCreated($created) {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated() {
        return $this->created;
    }

    /**
     * @return array
     */
    function jsonSerialize() {
        return [
            'id' => $this->id,
            'userId' => $this->userId,
            'operatorId' => $this->operatorId,
            'duration' => $this->duration,
            'comment' => $this->comment,
            'updated' => $this->updated,
            'created' => $this->created
        ];
    }
}
