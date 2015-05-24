<?php
namespace Entity;


use Doctrine\ORM\EntityManager;

/**
 * CharityCategory
 *
 * @Table(name="CharityCategory")
 * @Entity
 */
class CharityCategory extends BaseEntity
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

	public function __construct($object) {
		$this->fromObject($object);

	}

	public function fromObject($object) {
		$this->categoryId = $object['categoryid'];
		$this->name = $object['category'];
		$this->description = $object['description'];
	}

	public static function updateFromObject(EntityManager $em, $object) {
		$repo = $em->getRepository('Entity\CharityCategory');
		/** @var CharityCategory $category  */
		$category = $repo->findOneBy(array('categoryId' => $object['categoryid']));
		if ($category === null) {
			$category = new CharityCategory($object);
		} else {
			$category->fromObject($object);
		}
		$em->persist($category);
		return $category;
	}

	public function getId() {
		return $this->id;
	}

	public function getName() {
		return $this->name;
	}

	static public function getAll(EntityManager $em) {
		$qb = $em->createQueryBuilder();
		$qb->select('cc');
		$qb->from('Entity\CharityCategory', 'cc');
		$qb->orderBy('cc.name');
		return $qb->getQuery()->getResult();
	}

    /**
     * @return CharityCause[]
     */
    public function getCauses() {
        return CharityCause::findBy(array('categoryId' => $this->id));
    }
}
