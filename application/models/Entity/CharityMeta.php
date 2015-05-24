<?php
namespace Entity;

use Doctrine\ORM\EntityManager;

/**
 * CharityMeta
 *
 * @Table(name="CharityMeta")
 * @Entity
 */
class CharityMeta extends BaseEntity
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
     * @var integer
     *
     * @Column(name="charityId", type="integer", nullable=false)
     */
    private $charityId;

    /**
     * @var string
     *
     * @Column(name="`key`", type="string", length=255, nullable=false)
     */
    private $key;

    /**
     * @var string
     *
     * @Column(name="`value`", type="string", nullable=false)
     */
    private $value;


	public function getId() {
		return $this->id;
	}

	public function getCharityId() {
		return $this->charityId;
	}

    public function getKey() {
        return $this->key;
    }

    public function getValue() {
        return $this->value;
    }

    public function setKey($key) {
        $this->key = $key;
    }

    public function setValue($value) {
        $this->value = $value;
    }

    public function setCharity(\Entity\Charity $charity) {
        $this->charityId = $charity->getId();
    }
}
