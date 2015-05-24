<?php
namespace Entity;

use Doctrine\ORM\EntityManager;

/**
 * CharityCity
 *
 * @Table(name="CharityCity")
 * @Entity
 */
class CharityCity extends BaseEntity
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
     * @Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

	/**
	 * @var integer
	 *
	 * @Column(name="stateId", type="integer", nullable=false)
	 */
	private $stateId;

	public function __construct($name) {
		$this->name = $name;
	}

	/**
	 * @param \Doctrine\ORM\EntityManager $em
	 * @param                             $name
	 *
	 * @return CharityCity
	 */
	static public function getOrCreateByNameAndState(EntityManager $em, $name, CharityState $state) {
		$repo = $em->getRepository('Entity\CharityCity');
		/** @var CharityCity|null $city */
		$city = $repo->findOneBy(array('name' => $name, 'stateId' => $state));
		if ($city === null) {
			$city = new CharityCity($name);
			$city->setStateId($state->getId());
			$em->persist($city);
			$em->flush($city);
		}
		return $city;
	}

	public function getId() {
		return $this->id;
	}

	public function getName() {
		return $this->name;
	}



	static public function getState(EntityManager $em, $stateId) {
		$qb = $em->createQueryBuilder();
		$qb->select('cc');
		$qb->from('Entity\CharityCity', 'cc');
		$qb->where('cc.stateId = :stateId');
		$qb->setParameter('stateId', $stateId);
		$qb->orderBy('cc.name');
		return $qb->getQuery()->getResult();
	}

	public function setStateId($stateId) {
		$this->stateId = $stateId;
	}

	public function getStateId() {
		return $this->stateId;
	}

    /**
     * @return CharityState
     */
    public function getStateEntity() {
        return CharityState::find($this->stateId);
    }

}
