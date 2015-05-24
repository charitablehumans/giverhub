<?php
namespace Entity;

/**
 * Bet
 *
 * @Table(name="charity_cause_keyword")
 * @Entity
 */
class CharityCauseKeyword extends BaseEntity {

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
     * @Column(name="keyword", type="string", nullable=false)
     */
    private $keyword;

    /**
     * @var integer
     *
     * @Column(name="charity_cause_id", type="integer", nullable=false)
     */
    private $charity_cause_id;

    /**
     * @var integer
     *
     * @Column(name="strength", type="integer", nullable=false)
     */
    private $strength = 0;

    /**
     * @param int $charity_cause_id
     */
    public function setCharityCauseId($charity_cause_id)
    {
        $this->charity_cause_id = $charity_cause_id;
    }

    /**
     * @return int
     */
    public function getCharityCauseId()
    {
        return $this->charity_cause_id;
    }

    /**
     * @param CharityCause $charity_cause
     */
    public function setCharityCause(CharityCause $charity_cause)
    {
        $this->charity_cause_id = $charity_cause->getId();
    }

    /**
     * @return CharityCause
     */
    public function getCharityCause()
    {
        return CharityCause::find($this->charity_cause_id);
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $keyword
     */
    public function setKeyword($keyword)
    {
        $this->keyword = $keyword;
    }

    /**
     * @return string
     */
    public function getKeyword()
    {
        return $this->keyword;
    }

    /**
     * @param int $strength
     */
    public function setStrength($strength)
    {
        $this->strength = $strength;
    }

    /**
     * @return int
     */
    public function getStrength()
    {
        return $this->strength;
    }

}
