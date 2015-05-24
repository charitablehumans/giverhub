<?php

namespace Entity;

/**
 * PetitionSignatureRemovalRequest
 *
 * @Table(name="petition_signature_removal_request", uniqueConstraints={@UniqueConstraint(name="uq_petition_signature_removal_request", columns={"user_id", "type", "signature_id"})}, indexes={@Index(name="fk_petition_signature_removal_request_user_idx", columns={"user_id"}), @Index(name="fk_petition_signature_removal_request_removed_by_user_idx", columns={"removed_by_user_id"})})
 * @Entity
 */
class PetitionSignatureRemovalRequest extends BaseEntity {
    /**
     * @var integer
     *
     * @Column(name="id", type="integer", nullable=false)
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    static public $allowed_types = ['signature','reason'];

    /**
     * @var string
     *
     * @Column(name="type", type="string", nullable=false)
     */
    private $type;

    /**
     * @var string
     *
     * @Column(name="reason", type="string", nullable=false)
     */
    private $reason;

    /**
     * @var integer
     *
     * @Column(name="signature_id", type="integer", nullable=false)
     */
    private $signatureId;

    /**
     * @var \DateTime
     *
     * @Column(name="date_added", type="datetime", nullable=false)
     */
    private $dateAdded;

    /**
     * @var \DateTime
     *
     * @Column(name="date_removed", type="datetime", nullable=true)
     */
    private $dateRemoved;

    /**
     * @var User
     *
     * @ManyToOne(targetEntity="User")
     * @JoinColumns({
     *   @JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;

    /**
     * @var User
     *
     * @ManyToOne(targetEntity="User")
     * @JoinColumns({
     *   @JoinColumn(name="removed_by_user_id", referencedColumnName="id")
     * })
     */
    private $removedByUser;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return \DateTime
     */
    public function getDateAdded()
    {
        return $this->dateAdded;
    }

    /**
     * @param \DateTime $dateAdded
     */
    public function setDateAdded(\DateTime $dateAdded)
    {
        $this->dateAdded = $dateAdded;
    }

    /**
     * @return \DateTime
     */
    public function getDateRemoved()
    {
        return $this->dateRemoved;
    }

    /**
     * @param \DateTime $dateRemoved
     */
    public function setDateRemoved(\DateTime $dateRemoved)
    {
        $this->dateRemoved = $dateRemoved;
    }

    /**
     * @return User
     */
    public function getRemovedByUser()
    {
        return $this->removedByUser;
    }

    /**
     * @param User $removedByUser
     */
    public function setRemovedByUser(User $removedByUser)
    {
        $this->removedByUser = $removedByUser;
    }

    /**
     * @return int
     */
    public function getSignatureId()
    {
        return $this->signatureId;
    }

    /**
     * @param int $signatureId
     */
    public function setSignatureId($signatureId)
    {
        $this->signatureId = $signatureId;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param $type
     *
     * @throws \Exception
     */
    public function setType($type)
    {
        if (!in_array($type, self::$allowed_types)) {
            throw new \Exception('Invalid type: ' . $type . ' allowed types: ' . join(', ', self::$allowed_types));
        }
        $this->type = $type;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return string
     */
    public function getReason()
    {
        return $this->reason;
    }

    /**
     * @param string $reason
     */
    public function setReason($reason)
    {
        $this->reason = $reason;
    }

    /**
     * @return ChangeOrgPetitionReason|ChangeOrgPetitionSignature|null
     */
    public function getEntity() {
        if ($this->type == 'reason') {
            return ChangeOrgPetitionReason::find($this->signatureId);
        } elseif ($this->type == 'signature') {
            return ChangeOrgPetitionSignature::find($this->signatureId);
        }
        return null;
    }
}
