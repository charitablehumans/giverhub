<?php

namespace Entity;

/**
 * GiverCoin
 *
 * @Table(name="giver_coin")
 * @Entity
 */
class GiverCoin extends BaseEntity
{
    /**
     * @var integer
     *
     * @Column(name="id", type="integer", nullable=false)
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @Column(name="event", type="string", length=45, nullable=false)
     */
    private $event;

    /**
     * @var float
     *
     * @Column(name="amount", type="float", nullable=false)
     */
    private $amount;

    /**
     * @var string
     *
     * @Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * @param string $event
     */
    public function setEvent( $event )
    {
        $this->event = $event;
    }

    /**
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     */
    public function setAmount( $amount )
    {
        $this->amount = $amount;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription( $description )
    {
        $this->description = $description;
    }
}
