<?php

namespace Entity;

/**
 * Blogs
 *
 * @Table(name="blog")
 * @Entity
 */
class Blogs extends BaseEntity {

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
     * @Column(name="title", type="string", nullable=false)
     */
    private $title;

    /**
     * @var string
     *
     * @Column(name="description", type="string", nullable=false)
     */
    private $description;

	/**
     * @var integer
     *
     * @Column(name="is_publish", type="integer", nullable=false)
     */
    private $is_publish = 0;

    /**
     * @var integer
     *
     * @Column(name="user_id", type="integer", nullable=false)
     */
    private $user_id;

	/**
     * @var string
     *
     * @Column(name="created_at", type="string", nullable=false)
     */
    private $created_at;

	/**
     * @var string
     *
     * @Column(name="updated_at", type="string", nullable=true)
     */
    private $updated_at;


	public function getId() {
        return $this->id;
    }
    

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }
	
	/**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

	/**
     * @param int $is_publish
     */
    public function setIsPublish($is_publish)
    {
        $this->is_publish = $is_publish;
    }

    /**
     * @return int
     */
    public function getIsPublish()
    {
        return $this->is_publish;
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
    public function setUserId(User $user) {
        $this->user_id = $user->getId();
    }

	/**
     * @param string $created_at
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    }

    /**
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

	/**
     * @param string $updated_at
     */
    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;
    }

    /**
     * @return string
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }    

}
