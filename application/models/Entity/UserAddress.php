<?php
namespace Entity;

/**
 * UserAddress
 *
 * @Table(name="user_address")
 * @Entity
 */
class UserAddress extends BaseEntity
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
     * @Column(name="state_id", type="integer", nullable=false)
     */
    private $state_id;

    /**
     * @var integer
     *
     * @Column(name="city_id", type="integer", nullable=false)
     */
    private $city_id;

    /**
     * @var integer
     *
     * @Column(name="zipcode", type="integer", nullable=false)
     */
    private $zipcode;

    /**
     * @var string
     *
     * @Column(name="address1", type="string", nullable=false)
     */
    private $address1;

    /**
     * @var string
     *
     * @Column(name="address2", type="string", nullable=true)
     */
    private $address2;

    /**
     * @var integer
     *
     * @Column(name="phone", type="integer", nullable=false)
     */
    private $phone;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $address1
     */
    public function setAddress1($address1)
    {
        $this->address1 = $address1;
    }

    /**
     * @return string
     */
    public function getAddress1()
    {
        return $this->address1;
    }

    /**
     * @param string $address2
     */
    public function setAddress2($address2)
    {
        $this->address2 = $address2;
    }

    /**
     * @return string
     */
    public function getAddress2()
    {
        return $this->address2;
    }

    /**
     * @param int $city_id
     */
    public function setCityId($city_id)
    {
        $this->city_id = $city_id;
    }

    /**
     * @return int
     */
    public function getCityId()
    {
        return $this->city_id;
    }

    /**
     * @param int $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return int
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param int $state_id
     */
    public function setStateId($state_id)
    {
        $this->state_id = $state_id;
    }

    /**
     * @return int
     */
    public function getStateId()
    {
        return $this->state_id;
    }

    /**
     * @param int $user_id
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user) {
        $this->user_id = $user->getId();
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
     * @param int $zipcode
     */
    public function setZipcode($zipcode)
    {
        $this->zipcode = $zipcode;
    }

    /**
     * @return int
     */
    public function getZipcode()
    {
        return str_pad($this->zipcode, 5, '0', STR_PAD_LEFT);
    }

    /**
     * @param CharityCity $city
     */
    public function setCity(CharityCity $city) {
        $this->city_id = $city->getId();
    }

    /**
     * @param CharityState $state
     */
    public function setState(CharityState $state) {
        $this->state_id = $state->getId();
    }

    public function getCity() {
        /** @var CharityCity $c */
        $c = CharityCity::find($this->city_id);
        return $c;
    }

    public function getState() {
        /** @var CharityState $s */
        $s = CharityState::find($this->state_id);
        return $s;
    }

    public function isDefault() {
        return $this->getUser()->getDefaultAddressId() == $this->id;
    }

    public function __toString() {
        $str = '';

        $str .= $this->address1;
        if ($this->address2) {
            $str .= ', ' . $this->address2;
        }
        $str .= ', ' . $this->getCity()->getName();
        $str .= ', ' . $this->getState()->getName();
        $str .= ' ' . $this->zipcode;

        return $str;
    }

}
