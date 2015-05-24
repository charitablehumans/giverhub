<?php
namespace Entity;

/**
 * CardOnFile
 *
 * @Table(name="card_on_file", indexes={@Index(name="fk_card_on_file_user_idx", columns={"user_id"})})
 * @Entity
 */
class CardOnFile extends BaseEntity
{
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
     * @Column(name="cof_id", type="integer", nullable=false)
     */
    private $cofId;

    /**
     * @var integer
     *
     * @Column(name="exp_month", type="integer", nullable=false)
     */
    private $expMonth;

    /**
     * @var integer
     *
     * @Column(name="exp_year", type="integer", nullable=false)
     */
    private $expYear;

    /**
     * @var integer
     *
     * @Column(name="suffix", type="integer", nullable=false)
     */
    private $suffix;

    /**
     * @var string
     *
     * @Column(name="type", type="string", length=45, nullable=false)
     */
    private $type;

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
     * @return int
     */
    public function getCofId()
    {
        return $this->cofId;
    }

    /**
     * @param int $cofId
     */
    public function setCofId($cofId)
    {
        $this->cofId = $cofId;
    }

    /**
     * @return int
     */
    public function getExpMonth()
    {
        return $this->expMonth;
    }

    /**
     * @param int $expMonth
     */
    public function setExpMonth($expMonth)
    {
        $this->expMonth = $expMonth;
    }

    /**
     * @return int
     */
    public function getExpYear()
    {
        return $this->expYear;
    }

    /**
     * @param int $expYear
     */
    public function setExpYear($expYear)
    {
        $this->expYear = $expYear;
    }

    /**
     * @return int
     */
    public function getSuffix()
    {
        return $this->suffix;
    }

    /**
     * @param int $suffix
     */
    public function setSuffix($suffix)
    {
        $this->suffix = $suffix;
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
    public function setType($type)
    {
        $this->type = $type;
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

    public function __toString() {
        return $this->type . ' *' . $this->suffix . ' expires: ' . $this->expMonth . '/' . $this->expYear;
    }

    static public function getOrCreateFromPost($card) {
        /** @var CardOnFile $cardOnFile */
        $cardOnFile = self::findOneBy(['cofId' => $card['COFId']]);
        if ($cardOnFile) {
            if ($cardOnFile->getUser() !== \Base_Controller::$staticUser) {
                throw new \Exception('attempting to use a card-on-file that does not belong to user. card_on_file-id: ' . $cardOnFile->getId() . ' user-id: ' . \Base_Controller::$staticUser->getId());
            }
            return $cardOnFile;
        }

        /*
         * CCExpMonth: 6
         * CCExpYear: 2015
         * CCSuffix: "1111"
         * COFId: 1252601
         * CardType: "Visa"
         */
        $cardOnFile = new self;
        $cardOnFile->setExpMonth($card['CCExpMonth']);
        $cardOnFile->setExpYear($card['CCExpYear']);
        $cardOnFile->setSuffix($card['CCSuffix']);
        $cardOnFile->setCofId($card['COFId']);
        $cardOnFile->setType($card['CardType']);
        $cardOnFile->setUser(\Base_Controller::$staticUser);
        \Base_Controller::$em->persist($cardOnFile);
        \Base_Controller::$em->flush($cardOnFile);

        return $cardOnFile;
    }
}
