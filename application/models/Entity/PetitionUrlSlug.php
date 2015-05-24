<?php

namespace Entity;

/**
 * PetitionUrlSlug
 *
 * @Table(name="petition_url_slug", uniqueConstraints={@UniqueConstraint(name="url_slug_UNIQUE", columns={"url_slug"})}, indexes={@Index(name="fk_petition_url_slug_petition_idx", columns={"petition_id"})})
 * @Entity
 */
class PetitionUrlSlug extends BaseEntity {
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
     * @Column(name="url_slug", type="string", length=255, nullable=false)
     */
    private $urlSlug;

    /**
     * @var Petition
     *
     * @ManyToOne(targetEntity="Petition")
     * @JoinColumns({
     *   @JoinColumn(name="petition_id", referencedColumnName="id")
     * })
     */
    private $petition;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param \Entity\Petition $petition
     */
    public function setPetition($petition)
    {
        $this->petition = $petition;
    }

    /**
     * @return \Entity\Petition
     */
    public function getPetition()
    {
        return $this->petition;
    }

    /**
     * @param string $urlSlug
     */
    public function setUrlSlug($urlSlug)
    {
        $this->urlSlug = $urlSlug;
    }

    /**
     * @return string
     */
    public function getUrlSlug()
    {
        return $this->urlSlug;
    }

}
