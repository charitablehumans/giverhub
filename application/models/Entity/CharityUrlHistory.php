<?php
namespace Entity;

/**
 * CharityUrlHistory
 *
 * @Table(name="charity_url_history", uniqueConstraints={@UniqueConstraint(name="url_slug_UNIQUE", columns={"url_slug"})}, indexes={@Index(name="fk_charity_url_history_charity_idx", columns={"charity_id"})})
 * @Entity
 */
class CharityUrlHistory extends BaseEntity {
    /**
     * @var integer
     *
     * @Column(name="id", type="integer", nullable=false)
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var Charity
     *
     * @ManyToOne(targetEntity="Charity")
     * @JoinColumns({
     *   @JoinColumn(name="charity_id", referencedColumnName="id")
     * })
     */
    private $charity;


    /**
     * @var string
     *
     * @Column(name="url_slug", type="string", length=255, nullable=false)
     */
    private $url_slug;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Charity
     */
    public function getCharity()
    {
        return $this->charity;
    }

    /**
     * @param Charity $charity
     */
    public function setCharity( $charity )
    {
        $this->charity = $charity;
    }

    /**
     * @return string
     */
    public function getUrlSlug()
    {
        return $this->url_slug;
    }

    /**
     * @param string $urlSlug
     */
    public function setUrlSlug( $urlSlug )
    {
        $this->url_slug = $urlSlug;
    }


}
