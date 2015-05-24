<?php
namespace Entity;

/**
 * GivingPotRecipient
 *
 * @Table(name="giving_pot_recipient", indexes={@Index(name="fk_giving_pot_recipient_pot_idx", columns={"giving_pot_id"}), @Index(name="fk_giving_pot_recipient_givercard_idx", columns={"givercard_id"})})
 * @Entity
 */
class GivingPotRecipient extends BaseEntity {
    /**
     * @var integer
     *
     * @Column(name="id", type="integer", nullable=false)
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var GivingPot
     *
     * @ManyToOne(targetEntity="GivingPot")
     * @JoinColumns({
     *   @JoinColumn(name="giving_pot_id", referencedColumnName="id")
     * })
     */
    private $givingPot;

    /**
     * @var GivercardTransactions
     *
     * @ManyToOne(targetEntity="GivercardTransactions")
     * @JoinColumns({
     *   @JoinColumn(name="givercard_id", referencedColumnName="id")
     * })
     */
    private $givercard;

    /**
     * @var string
     *
     * @Column(name="name", type="string", length=45, nullable=true)
     */
    private $name;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId( $id )
    {
        $this->id = $id;
    }

    /**
     * @return GivingPot
     */
    public function getGivingPot()
    {
        return $this->givingPot;
    }

    /**
     * @param GivingPot $givingPot
     */
    public function setGivingPot( $givingPot )
    {
        $this->givingPot = $givingPot;
    }

    /**
     * @return GivercardTransactions
     */
    public function getGivercard()
    {
        return $this->givercard;
    }

    /**
     * @param GivercardTransactions $givercard
     */
    public function setGivercard( $givercard )
    {
        $this->givercard = $givercard;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName( $name )
    {
        $this->name = $name;
    }

    public function getEmail() {
        if ($this->givercard->getToUserId()) {
            return $this->givercard->getToUser()->getEmail();
        } elseif ($this->givercard->getToEmail()) {
            return $this->givercard->getToEmail();
        } else {
            throw new \Exception('This is awkward, the pot recipient does not have an email.');
        }
    }
}
