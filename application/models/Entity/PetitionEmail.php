<?php

namespace Entity;

/**
 * PetitionEmail
 *
 * @Table(name="petition_email", uniqueConstraints={@UniqueConstraint(name="uq_petition_email", columns={"email", "petition_id"})}, indexes={@Index(name="fk_petition_email_petition_idx", columns={"petition_id"})})
 * @Entity
 */
class PetitionEmail extends BaseEntity {
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
     * @Column(name="email", type="string", length=255, nullable=false)
     */
    private $email;

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
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

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
}
