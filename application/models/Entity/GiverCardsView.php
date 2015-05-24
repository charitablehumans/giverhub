<?php

namespace Entity;

/**
 * Giverhub Cards
 *
 * @Table(name="giver_cards_view")
 * @Entity
 */
class GiverCardsView extends BaseEntity {

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
     * @Column(name="time_checked", type="string")
     *
     */
    private $time_checked;

    public function getId() {
        return $this->id;
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
     * @param string $time_checked
     */
    public function setTimeChecked($time_checked)
    {
        $this->time_checked = $time_checked;
    }

    /**
     * @return string
     */
    public function getTimeChecked()
    {
        return $this->time_checked;
    }

    /**
     * @param array $criteria
     * @param array $orderBy
     *
     * @return Giver Cards
     */
    static public function findOneBy(array $criteria, array $orderBy = null) {
        return \Base_Controller::$em->getRepository('\Entity\GiverCardsView')->findOneBy($criteria, $orderBy);
    }

}
