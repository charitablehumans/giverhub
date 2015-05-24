<?php

namespace Entity;

/**
 * SitemapPages
 *
 * @Table(name="sitemap_pages", uniqueConstraints={@UniqueConstraint(name="uq_sitemap_page", columns={"letter", "type", "page", "entity_id"})})
 * @Entity
 */
class SitemapPages extends BaseEntity {
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
     * @Column(name="letter", type="string", length=2, nullable=false)
     */
    private $letter;

    static public $types = ['nonprofit', 'petition'];
    /**
     * @var string
     *
     * @Column(name="type", type="string", nullable=false)
     */
    private $type;

    /**
     * @var integer
     *
     * @Column(name="page", type="integer", nullable=false)
     */
    private $page;

    /**
     * @var integer
     *
     * @Column(name="entity_id", type="integer", nullable=false)
     */
    private $entityId;

    /**
     * @return int
     */
    public function getEntityId()
    {
        return $this->entityId;
    }

    /**
     * @param int $entityId
     */
    public function setEntityId( $entityId )
    {
        $this->entityId = $entityId;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getLetter()
    {
        return $this->letter;
    }

    /**
     * @param string $letter
     */
    public function setLetter( $letter )
    {
        $this->letter = $letter;
    }

    /**
     * @return int
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @param int $page
     */
    public function setPage( $page )
    {
        $this->page = $page;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType( $type )
    {
        if (!in_array($type, self::$types)) {
            throw new \Exception('invalid type: ' . $type);
        }
        $this->type = $type;
    }

    static public function update($letter, $type, $page, BaseEntity $entity, $flush = true) {
        /** @var ChangeOrgPetition|Charity $entity */

        /** @var SitemapPages $sitemap_page */
        $sitemap_page = self::findOneBy(['letter' => $letter, 'type' => $type, 'entityId' => $entity->getId()]);
        if (!$sitemap_page) {
            $sitemap_page = new self;
            $sitemap_page->setLetter($letter);
            $sitemap_page->setType($type);
            $sitemap_page->setEntityId($entity->getId());
        }
        $sitemap_page->setPage($page);

        \Base_Controller::$em->persist($sitemap_page);
        if ($flush) {
            \Base_Controller::$em->flush( $sitemap_page );
        }
        return $sitemap_page;
    }


    /**
     * @return Charity|ChangeOrgPetition
     * @throws \Exception
     */
    public function getEntity() {
        if ($this->type == 'nonprofit') {
            $entity = Charity::find($this->entityId);
        } else {
            $entity = Petition::find($this->entityId);
        }
        if (!$entity) {
            throw new \Exception('failed to load entity of type: ' . $this->type . ' please rerun sitemap/generate_cache');
        }
        return $entity;
    }


}
