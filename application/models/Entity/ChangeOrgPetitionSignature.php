<?php

namespace Entity;


/**
 * ChangeOrgPetitionSignature
 *
 * @Table(name="change_org_petition_signature")
 * @Entity
 */
class ChangeOrgPetitionSignature extends BaseEntity {

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
     * @Column(name="name", type="string", nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @Column(name="city", type="string", nullable=true)
     */
    private $city;

    /**
     * @var string
     *
     * @Column(name="state", type="string", nullable=true)
     */
    private $state;

    /**
     * @var string
     *
     * @Column(name="country_name", type="string", nullable=true)
     */
    private $country_name;

    /**
     * @var string
     *
     * @Column(name="country_code", type="string", nullable=true)
     */
    private $country_code;

    /**
     * @var string
     *
     * @Column(name="signed_on", type="string", nullable=false)
     */
    private $signed_on;

    /**
     * @param string $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $country_code
     */
    public function setCountryCode($country_code)
    {
        $this->country_code = $country_code;
    }

    /**
     * @return string
     */
    public function getCountryCode()
    {
        return $this->country_code;
    }

    /**
     * @param string $country_name
     */
    public function setCountryName($country_name)
    {
        $this->country_name = $country_name;
    }

    /**
     * @return string
     */
    public function getCountryName()
    {
        return $this->country_name;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
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
     * @param string $signed_on
     */
    public function setSignedOn($signed_on)
    {
        $this->signed_on = $signed_on;
    }

    /**
     * @param \DateTime $signed_on
     */
    public function setSignedOnDt(\DateTime $signed_on)
    {
        $this->signed_on = $signed_on->format('Y-m-d H:i:s');
    }

    /**
     * @return string
     */
    public function getSignedOn()
    {
        return $this->signed_on;
    }

    /**
     * @return \DateTime
     */
    public function getSignedOnDt()
    {
        return new \DateTime($this->signed_on);
    }

    /**
     * @param string $state
     */
    public function setState($state)
    {
        $this->state = $state;
    }

    /**
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @return string
     */
    public function getIntervalFromNow() {
        $now = new \DateTime();
        $interval = $now->diff($this->getSignedOnDt());
        $mappings = array(
            'y' => 'years',
            'm' => 'months',
            'd' => 'days',
            'h' => 'hrs',
            'i' => 'mins',
            's' => 'secs',
        );
        foreach($mappings as $col => $str) {
            if ($interval->$col) {
                return $interval->$col . ' ' . $str;
            }
        }
        return 'now';
    }

    public function getPetition() {
        return ChangeOrgPetition::find($this->petition_id);
    }
}
