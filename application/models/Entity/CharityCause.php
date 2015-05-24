<?php
namespace Entity;

use Doctrine\ORM\EntityManager;

/**
 * CharityCause
 *
 * @Table(name="CharityCause")
 * @Entity
 */
class CharityCause extends BaseEntity
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
	 * @var string
	 *
	 * @Column(name="description", type="string", length=255, nullable=false)
	 */
	private $description;

	/**
	 * @var integer
	 *
	 * @Column(name="categoryId", type="integer", nullable=false)
	 */
	private $categoryId;

	public function getId() {
		return $this->id;
	}

	public function getName() {
		return $this->name;
	}

	static public function getAll(EntityManager $em) {
		$qb = $em->createQueryBuilder();
		$qb->select('cc');
		$qb->from('Entity\CharityCause', 'cc');
		$qb->orderBy('cc.name');
		return $qb->getQuery()->getResult();
	}

    /**
     * @param array $criteria
     * @param array $orderBy
     * @param null  $limit
     * @param null  $offset
     *
     * @return CharityCause[]
     */
    static public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null) {
        return \Base_Controller::$em->getRepository('\Entity\CharityCause')->findBy($criteria, $orderBy, $limit, $offset);
    }

    /**
     * @return \Entity\CharityCategory
     */
    public function getCategory() {
        return \Base_Controller::$em->getRepository('Entity\CharityCategory')->find($this->categoryId);
    }

    /**
     * @return CharityCauseKeyword[]
     */
    public function getKeywords() {
        return CharityCauseKeyword::findBy(['charity_cause_id' => $this->getId()]);
    }
}
