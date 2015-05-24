<?php
namespace Entity;

/**
 * UserSetting
 *
 * @Table(name="user_setting", uniqueConstraints={@UniqueConstraint(name="uq_user_setting", columns={"user_id", "setting_id"})}, indexes={@Index(name="fk_user_setting_setting_idx", columns={"setting_id"}), @Index(name="fk_user_setting_user_idx", columns={"user_id"})})
 * @Entity
 */
class UserSetting extends BaseEntity {
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
     * @Column(name="value", type="text", nullable=false)
     */
    private $value;

    /**
     * @var Setting
     *
     * @ManyToOne(targetEntity="Setting")
     * @JoinColumns({
     *   @JoinColumn(name="setting_id", referencedColumnName="id")
     * })
     */
    private $setting;

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
     * @return Setting
     */
    public function getSetting()
    {
        return $this->setting;
    }

    /**
     * @param Setting $setting
     */
    public function setSetting( $setting )
    {
        $this->setting = $setting;
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
    public function setUser( $user )
    {
        $this->user = $user;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function setValue( $value )
    {
        $this->value = $value;
    }
}
