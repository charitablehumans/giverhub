<?php
namespace Entity;

/**
 * Profanity
 *
 * @Table(name="profanity")
 * @Entity
 */
class Profanity extends BaseEntity {

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
     * @Column(name="profanity", type="string", nullable=false)
     */
    private $profanity;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $profanity
     */
    public function setProfanity($profanity)
    {
        $this->profanity = $profanity;
    }

    /**
     * @return string
     */
    public function getProfanity()
    {
        return $this->profanity;
    }

}
