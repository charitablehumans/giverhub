<?php
namespace Entity;

/**
 * CharityCategory
 *
 * @Table(name="charity_change_history")
 * @Entity
 */
class CharityChangeHistory extends BaseEntity
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
	 * @var string
	 *
	 * @Column(name="field", type="string", nullable=false)
	 */
	private $field;

    public static $allowedFields = ['mission', 'tagline'];

    /**
     * @var string
     *
     * @Column(name="datetime", type="string", nullable=false)
     */
    private $datetime;

    /**
     * @var integer
     *
     * @Column(name="charity_id", type="integer", nullable=false)
     */
    private $charity_id;

    /**
     * @var string
     *
     * @Column(name="old_value", type="string", nullable=true)
     */
    private $old_value;

    /**
     * @var string
     *
     * @Column(name="new_value", type="string", nullable=false)
     */
    private $new_value;

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
     * @param Charity $charity
     */
    public function setCharity(Charity $charity) {
        $this->charity_id = $charity->getId();
    }

    /**
     * @return Charity
     */
    public function getCharity() {
        return Charity::find($this->charity_id);
    }

    /**
     * @param string $datetime
     */
    public function setDatetime($datetime)
    {
        $this->datetime = $datetime;
    }

    /**
     * @return string
     */
    public function getDatetime()
    {
        return $this->datetime;
    }

    /**
     * @param \DateTime $dt
     */
    public function setDatetimeDt(\DateTime $dt) {
        $this->datetime = $dt->format('Y-m-d H:i:s');
    }

    /**
     * @return \DateTime
     */
    public function getDatetimeDt() {
        return new \DateTime($this->datetime);
    }

    /**
     * @param $field
     *
     * @throws \Exception
     */
    public function setField($field)
    {
        if (!in_array($field, self::$allowedFields)) {
            throw new \Exception('Bad field ' . $field . ' allowed fields: ' . join(', ', self::$allowedFields));
        }
        $this->field = $field;
    }

    /**
     * @return string
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $new_value
     */
    public function setNewValue($new_value)
    {
        $this->new_value = $new_value;
    }

    /**
     * @return string
     */
    public function getNewValue()
    {
        return $this->new_value;
    }

    /**
     * @param string $old_value
     */
    public function setOldValue($old_value)
    {
        $this->old_value = $old_value;
    }

    /**
     * @return string
     */
    public function getOldValue()
    {
        return $this->old_value;
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
        return User::find($this->user_id);
    }

    /**
     * @param User $user
     */
    public function setUser(User $user) {
        $this->user_id = $user->getId();
    }
}
