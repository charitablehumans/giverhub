<?php
namespace Entity;

use Doctrine\ORM\EntityManager;

/**
 * CharityCause
 *
 * @Table(name="CharityCharityCause")
 * @Entity
 */
class CharityCharityCause extends BaseEntity
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
	 * @var integer
	 *
	 * @Column(name="causeId", type="integer", nullable=false)
	 */
	private $causeId;

    /**
     * @var integer
     *
     * @Column(name="from_charity_navigator", type="integer", nullable=false)
     */
    private $from_charity_navigator = 0;



    public function getId() {
		return $this->id;
	}

    /**
     * @return \Entity\Charity
     */
    public function getCharity() {
        return \Base_Controller::$em->getRepository('\Entity\Charity')->find($this->charityId);
	}

    /**
     * @return \Entity\CharityCause
     */
    public function getCause() {
        return \Base_Controller::$em->getRepository('\Entity\CharityCause')->find($this->causeId);
    }

    /**
     * @param array $criteria
     * @param array $orderBy
     * @param null  $limit
     * @param null  $offset
     *
     * @return CharityCharityCause[]
     */
    static public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null) {
        return \Base_Controller::$em->getRepository('\Entity\CharityCharityCause')->findBy($criteria, $orderBy, $limit, $offset);
    }

    public function setCause(CharityCause $cause) {
        $this->causeId = $cause->getId();
    }

    public function setCharity(Charity $charity) {
        $this->charityId = $charity->getId();
    }

    public function setFromCharityNavigator($val) {
        $this->from_charity_navigator = $val;
    }

    public function getFromCharityNavigator() {
        return $this->from_charity_navigator;
    }
}
