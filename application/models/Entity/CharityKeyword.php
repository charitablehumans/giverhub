<?php
namespace Entity;

/**
 * CharityKeyword
 *
 * @Table(name="charity_keyword")
 * @Entity @HasLifecycleCallbacks
 */
class CharityKeyword extends BaseEntity
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
     * @var string
     *
     * @Column(name="keyword_name", type="string", length=255, nullable=false)
     */
    private $keyword_name;

	/**
	 * @var integer
	 *
	 * @Column(name="charity_id", type="integer", nullable=false)
	 */
	private $charity_id;

    /**
     * @var integer
     *
     * @Column(name="user_id", type="integer", nullable=false)
     */
    private $user_id;

    /**
     * @var integer
     *
     * @Column(name="date", type="string", nullable=false)
     */
    private $date;

	public function getId() {
		return $this->id;
	}

	public function getKeywordName() {
		return $this->keyword_name;
	}

	public function setKeywordName($key) {
		$this->keyword_name = $key;
	}

	public function setCharityId($charityId) {
		$this->charity_id = $charityId;
	}

	public function getCharityId() {
		return $this->charity_id;
	}

    /**
     * @return Charity
     */
    public function getCharity() {
        return Charity::find($this->charity_id);
    }

    /**
     * @param User $user
     */
    public function setUser(User $user) {
        $this->user_id = $user->getId();
    }

    /**
     * @param Charity $charity
     */
    public function setCharity(Charity $charity) {
        $this->charity_id = $charity->getId();
    }

    public function setDate($date) {
        $this->date = $date;
    }

    /** @var CharityKeywordVote[] */
    private $votes;

    public function getUpVotes() {
        if ($this->votes === null) {

            $this->votes = \Base_Controller::$em->getRepository('\Entity\CharityKeywordVote')->findBy(array(
                                                                                                           'keyword_id' => $this->id
                                                                                                      ));
        }
        $upVotes = 0;
        foreach($this->votes as $vote) {
            if ($vote->getVote() > 0) {
                $upVotes++;
            }
        }
        return $upVotes;
    }

    public function getDownVotes() {
        if ($this->votes === null) {

            $this->votes = \Base_Controller::$em->getRepository('\Entity\CharityKeywordVote')->findBy(array(
                                                                                                           'keyword_id' => $this->id
                                                                                                      ));
        }
        $downVotes = 0;
        foreach($this->votes as $vote) {
            if ($vote->getVote() < 0) {
                $downVotes++;
            }
        }
        return $downVotes;
    }

    public function getFlagged() {
        /** @var CharityKeywordFlag[] $flags */
        $flags = \Base_Controller::$em->getRepository('\Entity\CharityKeywordFlag')->findBy(array(
                                                                                                 'keyword_id' => $this->id,
                                                                                            ));
        return count($flags);
    }

    public function vote($vote) {
        $user = \Base_Controller::$staticUser;
        /** @var CharityKeywordVote $existing */
        $existing = CharityKeywordVote::findOneBy(array(
                                        'user_id' => $user->getId(),
                                        'keyword_id' => $this->id,
                                   ));
        if (!$existing) {
            $existing = new CharityKeywordVote;
            $existing->setKeyword($this);
            $existing->setUser($user);
        }
        $existing->setVote($vote);

        \Base_Controller::$em->persist($existing);
        \Base_Controller::$em->flush($existing);
    }

    public function flag() {
        $user = \Base_Controller::$staticUser;
        /** @var CharityKeywordFlag $existing */
        $existing = CharityKeywordFlag::findOneBy(array(
                                                       'user_id' => $user->getId(),
                                                       'keyword_id' => $this->id,
                                                  ));

        if (!$existing) {
            $existing = new CharityKeywordFlag;
            $existing->setKeyword($this);
            $existing->setUser($user);
            $existing->setFlaggedTimeDt(new \DateTime());
            \Base_Controller::$em->persist($existing);
            \Base_Controller::$em->flush($existing);
        }
    }

    /** @PreRemove */
    public function onPreRemove()
    {
        $em = \Base_Controller::$em;
        $votes = CharityKeywordVote::findBy(['keyword_id' => $this->id]);
        foreach($votes as $vote) {
            $em->remove($vote);
        }
        $flags = CharityKeywordFlag::findBy(['keyword_id' => $this->id]);
        foreach($flags as $flag) {
            $em->remove($flag);
        }
    }
}
