<?php

namespace Entity;


/**
 * Badge
 *
 * @Table(name="badge")
 * @Entity
 */
class Badge extends BaseEntity {

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
     * @Column(name="user_id", type="integer", nullable=false)
     */
    private $user_id;

    /**
     * @var integer
     *
     * @Column(name="category_id", type="integer", nullable=false)
     */
    private $category_id;

    /**
     * @var integer
     *
     * @Column(name="points", type="integer", nullable=false)
     */
    private $points;

    /**
     * @param int $category_id
     */
    public function setCategoryId($category_id)
    {
        $this->category_id = $category_id;
    }

    /**
     * @return int
     */
    public function getCategoryId()
    {
        return $this->category_id;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param float $points
     */
    public function setPoints($points)
    {
        $this->points = $points;
    }

    /**
     * @return float
     */
    public function getPoints()
    {
        return $this->points;
    }

    /**
     * @param int $user_id
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * @return User
     */
    public function getUser() {
        /** @var \Entity\User $user */
        $user = User::find($this->user_id);
        return $user;
    }

    /**
     * @return \Entity\CharityCategory
     */
    public function getCategory() {
        return \Base_Controller::$em->getRepository('Entity\CharityCategory')->find($this->category_id);
    }

    /**
     * @param CharityCategory $category
     */
    public function setCategory(CharityCategory $category) {
        $this->category_id = $category->getId();
    }

    /**
     * @param User $user
     */
    public function setUser(User $user) {
        $this->user_id = $user->getId();
    }


    /**
     * @param array $criteria
     * @param array $orderBy
     * @param null  $limit
     * @param null  $offset
     *
     * @return Badge[]
     */
    static public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null) {
        return \Base_Controller::$em->getRepository('\Entity\Badge')->findBy($criteria, $orderBy, $limit, $offset);
    }
}
