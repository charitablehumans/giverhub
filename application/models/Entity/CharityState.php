<?php
namespace Entity;

use Doctrine\ORM\EntityManager;

/**
 * CharityState
 *
 * @Table(name="CharityState")
 * @Entity
 */
class CharityState extends BaseEntity
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
     * @Column(name="name", type="string", length=2, nullable=false)
     */
    private $name;

	/**
	 * @var string
	 *
	 * @Column(name="fullName", type="string", length=255, nullable=false)
	 */
	private $fullName;

	public function __construct($name = null, $fullName = null) {
		$this->name = $name;
        $this->fullName = $fullName;
	}

    /**
     * @param EntityManager $em
     * @param               $name
     *
     * @return CharityState|null
     */
    static public function getOrCreateByName(EntityManager $em, $name) {
		$repo = $em->getRepository('Entity\CharityState');
		/** @var CharityState|null $state */
		$state = $repo->findOneBy(array('name' => $name));
		if ($state === null) {
			$state = new CharityState($name);
			$em->persist($state);
			$em->flush($state);
		}
		return $state;
	}

	public function getId() {
		return $this->id;
	}

	public function setFullName($fullName) {
		$this->fullName = $fullName;
	}

	static public function getAll(EntityManager $em) {
		$qb = $em->createQueryBuilder();
		$qb->select('cs');
		$qb->from('Entity\CharityState', 'cs');
		$qb->orderBy('cs.name');
		return $qb->getQuery()->getResult();
	}

	public function getName() {
		return $this->name;
	}

	public function getFullName() {
		return $this->fullName;
	}

    /**
     * @return \Entity\CharityCity[]
     */
    public function getCities() {
        $cityRepo = \Base_Controller::$em->getRepository('Entity\CharityCity');
        return $cityRepo->findBy(array('stateId' => $this->id), array('name' => 'asc'));
    }
}
