<?php
namespace Entity;

/**
 * CharityState
 *
 * @Table(name="charity_update")
 * @Entity
 */
class CharityUpdate extends BaseEntity
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
     * @Column(name="charity_id", type="integer", nullable=false)
     */
    private $charity_id;

    /**
     * @var integer
     *
     * @Column(name="user_id", type="integer", nullable=false)
     */
    private $user_id;

    /**
     * @var string
     *
     * @Column(name="`date`", type="string", nullable=false)
     */
    private $date;

    /**
     * @var string
     *
     * @Column(name="`update`", type="string", nullable=false)
     */
    private $update;

    /**
     * @param int $charity_id
     */
    public function setCharityId($charity_id)
    {
        $this->charity_id = $charity_id;
    }

    /**
     * @return int
     */
    public function getCharityId()
    {
        return $this->charity_id;
    }

    /**
     * @param string $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return string
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
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
     * @return Charity
     */
    public function getCharity() {
        return Charity::find($this->charity_id);
    }

    /**
     * @param Charity $charity
     */
    public function setCharity(Charity $charity) {
        $this->charity_id = $charity->getId();
    }

    /**
     * @return User
     */
    public function getUser() {
        return User::find($this->user_id);
    }

    /**
     * @param User $user
     */
    public function setUser(User $user) {
        $this->user_id = $user->getId();
    }

    /**
     * @return \DateTime
     */
    public function getDateTime() {
        return new \DateTime($this->date);
    }

    /**
     * @param \DateTime $date
     */
    public function setDateTime(\DateTime $date) {
        $this->date = $date->format('Y-m-d H:i:s');
    }

    /**
     * @param string $update
     */
    public function setUpdate($update)
    {
        $this->update = $update;
    }

    /**
     * @return string
     */
    public function getUpdate()
    {
        return $this->update;
    }

    /**
     * @param array $criteria
     * @param array $orderBy
     * @param int   $limit
     * @param null  $offset
     *
     * @return CharityUpdate[]
     */
    static public function findBy(array $criteria = array(), array $orderBy = null, $limit = 3, $offset = null) {
        return \Base_Controller::$em->getRepository('\Entity\CharityUpdate')->findBy($criteria,$orderBy,$limit, $offset);
    }
}
