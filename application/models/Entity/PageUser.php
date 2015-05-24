<?php
namespace Entity;

/**
 * PageUser
 *
 * @Table(name="page_user", uniqueConstraints={@UniqueConstraint(name="uq_page_user", columns={"page_id", "user_id"})}, indexes={@Index(name="fk_page_user_page_idx", columns={"page_id"}), @Index(name="fk_page_user_user_idx", columns={"user_id"})})
 * @Entity
 */
class PageUser extends BaseEntity {
    /**
     * @var integer
     *
     * @Column(name="id", type="integer", nullable=false)
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var Page
     *
     * @ManyToOne(targetEntity="Page")
     * @JoinColumns({
     *   @JoinColumn(name="page_id", referencedColumnName="id")
     * })
     */
    private $page;

    /**
     * @var User
     *
     * @ManyToOne(targetEntity="User")
     * @JoinColumns({
     *   @JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Page
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @param Page $page
     */
    public function setPage(Page $page)
    {
        $this->page = $page;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }


}
