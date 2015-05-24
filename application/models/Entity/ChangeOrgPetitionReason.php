<?php

namespace Entity;


/**
 * ChangeOrgPetitionReason
 *
 * @Table(name="change_org_petition_reason")
 * @Entity
 */
class ChangeOrgPetitionReason extends BaseEntity {

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
     * @Column(name="content", type="string", nullable=false)
     */
    private $content;

    /**
     * @var integer
     *
     * @Column(name="like_count", type="integer", nullable=false)
     */
    private $like_count;

    /**
     * @var string
     *
     * @Column(name="author_name", type="string", nullable=true)
     */
    private $author_name;

    /**
     * @var string
     *
     * @Column(name="author_url", type="string", nullable=true)
     */
    private $author_url;

    /**
     * @var string
     *
     * @Column(name="created_on", type="string", nullable=false)
     */
    private $created_on;

    /**
     * @param string $created_on
     */
    public function setCreatedOn($created_on)
    {
        $this->created_on = $created_on;
    }

    /**
     * @param \DateTime $created_on
     */
    public function setCreatedOnDt(\DateTime $created_on)
    {
        $this->created_on = $created_on->format('Y-m-d H:i:s');
    }

    /**
     * @return string
     */
    public function getCreatedOn()
    {
        return $this->created_on;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedOnDt()
    {
        return new \DateTime($this->created_on);
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $petition_id
     */
    public function setPetitionId($petition_id)
    {
        $this->petition_id = $petition_id;
    }

    public function setPetition(ChangeOrgPetition $petition)
    {
        $this->petition_id = $petition->getId();
    }

    /**
     * @return int
     */
    public function getPetitionId()
    {
        return $this->petition_id;
    }

    /**
     * @param string $author_name
     */
    public function setAuthorName($author_name)
    {
        $this->author_name = $author_name;
    }

    /**
     * @return string
     */
    public function getAuthorName()
    {
        return $this->author_name;
    }

    /**
     * @param string $author_url
     */
    public function setAuthorUrl($author_url)
    {
        $this->author_url = $author_url;
    }

    /**
     * @return string
     */
    public function getAuthorUrl()
    {
        return $this->author_url;
    }

    /**
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param int $like_count
     */
    public function setLikeCount($like_count)
    {
        $this->like_count = $like_count;
    }

    /**
     * @return int
     */
    public function getLikeCount()
    {
        return $this->like_count;
    }

    public function getPetition() {
        return ChangeOrgPetition::find($this->petition_id);
    }
}
