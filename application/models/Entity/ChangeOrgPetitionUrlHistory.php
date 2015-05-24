<?php

namespace Entity;


/**
 * ChangeOrgPetition
 *
 * @Table(name="change_org_petition_url_history")
 * @Entity @HasLifecycleCallbacks
 */
class ChangeOrgPetitionUrlHistory extends BaseEntity {

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
     * @Column(name="giverhub_url_slug", type="string", nullable=false)
     */
    private $giverhub_url_slug;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $giverhub_url_slug
     */
    public function setGiverhubUrlSlug($giverhub_url_slug)
    {
        $this->giverhub_url_slug = $giverhub_url_slug;
    }

    /**
     * @return string
     */
    public function getGiverhubUrlSlug()
    {
        return $this->giverhub_url_slug;
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
     * @param ChangeOrgPetition $petition
     */
    public function setPetition(ChangeOrgPetition $petition) {
        $this->petition_id = $petition->getId();
    }

    /**
     * @return ChangeOrgPetition
     */
    public function getPetition() {
        return ChangeOrgPetition::find($this->petition_id);
    }
}
