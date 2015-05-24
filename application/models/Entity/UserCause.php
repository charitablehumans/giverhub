<?php
namespace Entity;

use Doctrine\ORM\EntityManager;

/**
 * UserCause
 *
 * @Table(name="UserCause")
 * @Entity
 */
class UserCause extends BaseEntity
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
     * @Column(name="userid", type="integer", nullable=false)
     */
    private $userid;

    /**
     * @var integer
     *
     * @Column(name="causeid", type="integer", nullable=false)
     */
    private $causeid;

    public function getId() {
        return $this->id;
    }

    public function getUserId() {
        return $this->userid;
    }

    public function getCauseId() {
        return $this->causeid;
    }

    /**
     * @return \Entity\CharityCause
     */
    public function getCharityCause() {
        $em = \Base_Controller::$em;
        $ccRepo = $em->getRepository('\Entity\CharityCause');
        return $ccRepo->find($this->causeid);
    }

    /**
     * @param User $user
     */
    public function setUser(User $user) {
        $this->userid = $user->getId();
    }

    /**
     * @param CharityCause $charityCause
     */
    public function setCharityCause(CharityCause $charityCause) {
        $this->causeid = $charityCause->getId();
    }
}
