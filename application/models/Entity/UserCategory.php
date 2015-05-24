<?php
namespace Entity;

/**
 * UserCategory
 *
 * @Table(name="user_category")
 * @Entity
 */
class UserCategory extends BaseEntity
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
     * @Column(name="user_id", type="integer", nullable=false)
     */
    private $user_id;

    /**
     * @var integer
     *
     * @Column(name="category_id", type="integer", nullable=false)
     */
    private $category_id;

    public function getId() {
        return $this->id;
    }

    public function getUserId() {
        return $this->user_id;
    }

    public function getCategoryId() {
        return $this->category_id;
    }

    /**
     * @return CharityCategory
     */
    public function getCharityCategory() {
        $em = \Base_Controller::$em;
        $ccRepo = $em->getRepository('\Entity\CharityCategory');
        return $ccRepo->find($this->category_id);
    }

    /**
     * @param User $user
     */
    public function setUser(User $user) {
        $this->user_id = $user->getId();
    }

    /**
     * @param CharityCategory $charityCategory
     */
    public function setCharityCategory(CharityCategory $charityCategory) {
        $this->category_id = $charityCategory->getId();
    }
}
