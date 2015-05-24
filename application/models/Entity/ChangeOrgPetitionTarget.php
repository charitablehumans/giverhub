<?php

namespace Entity;


/**
 * ChangeOrgPetitionTarget
 *
 * @Table(name="change_org_petition_target")
 * @Entity
 */
class ChangeOrgPetitionTarget extends BaseEntity {

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
     * @Column(name="petition_id", type="integer", nullable=false)
     */
    private $petition_id;

    /**
     * @var string
     *
     * @Column(name="name", type="string", nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @Column(name="type", type="string", nullable=false)
     */
    private $type;

    /**
     * @var integer
     *
     * @Column(name="first", type="integer", nullable=false)
     */
    private $first = 0;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param int $petition_id
     */
    public function setPetitionId($petition_id)
    {
        $this->petition_id = $petition_id;
    }

    /**
     * @return int
     */
    public function getPetitionId()
    {
        return $this->petition_id;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    public function setPetition(ChangeOrgPetition $petition) {
        $this->petition_id = $petition->getId();
    }

    /**
     * @param ChangeOrgPetition $petition
     * @param                   $name
     * @param                   $type
     * @param int               $first
     *
     * @return bool
     */
    static public function createOrUpdate(ChangeOrgPetition $petition, $name, $type, $first = 0) {
        $target = self::findOneBy(array('petition_id' => $petition->getId(), 'name' => $name));
        if (!$target) {
            $target = new self;
            $target->setPetition($petition);
        }

        $target->setName($name);
        $target->setType($type);
        $target->setFirst($first);

        \Base_Controller::$em->persist($target);
        \Base_Controller::$em->flush($target);

        return true;
    }

    /**
     * @return int
     */
    public function getFirst()
    {
        return $this->first;
    }

    /**
     * @param int $first
     */
    public function setFirst($first)
    {
        $this->first = $first;
    }

}
