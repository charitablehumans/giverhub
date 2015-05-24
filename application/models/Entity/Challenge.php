<?php
namespace Entity;

/**
 * Challenge
 *
 * @Table(name="challenge", indexes={@Index(name="fk_challenge_from_user_idx", columns={"from_user_id"}), @Index(name="fk_challenge_charity_idx", columns={"charity_id"})})
 * @Entity @HasLifecycleCallbacks
 */
class Challenge extends BaseEntity implements \JsonSerializable {

    /** @PrePersist */
    public function onPrePersist() {
        $this->createdDate = new \DateTime();
    }

    /** @PreUpdate */
    public function onPreUpdate() {
        $this->updatedDate = new \DateTime();
    }

    public function jsonSerialize() {
        $ret = [
            'id' => $this->id,
            'name' => $this->name,
            'nameWithChallenge' => $this->getNameWithChallenge(),
            'description' => $this->description,
            'youtubeVideoId' => $this->youtubeVideoId,
            'createdDate' => $this->createdDate->format('Y-m-d H:i:s'),
            'updatedDate' => $this->updatedDate ? $this->updatedDate->format('Y-m-d H:i:s') : null,
            'fromUser' => [
                'id' => $this->fromUser->getId(),
                'name' => $this->fromUser->getName(),
                'url' => $this->fromUser->getUrl(),
                'link' => $this->fromUser->getLink(),
            ],
            'charity' => [
                'id' => $this->charity->getId(),
                'name' => $this->charity->getName(),
                'url' => $this->charity->getUrl(),
                'link' => $this->charity->getLink(),
                'searchDesc' => $this->charity->getSearchDesc(),
            ],
            'dedication' => $this->dedication,
            'url' => $this->getUrl(),
            'link' => $this->getLink(),
            'challenge_users' => [],
        ];

        foreach($this->getChallengeUsers() as $challenge_user) {
            $is_fb_friend = $challenge_user->getUserEntity() instanceof FacebookFriend ? true : false;
            $ret['challenge_users'][] = [
                'challenge_user_id' => $challenge_user->getId(),
                'status' => $challenge_user->getStatus(),
                'is_fb_friend' => $is_fb_friend,
                'fb_user_id' => $is_fb_friend ? $challenge_user->getFbUser()->getFbId() : null,
                'user_id' => $is_fb_friend ? $challenge_user->getFbUser()->getId() : $challenge_user->getUser()->getId(),
                'name' => $challenge_user->getUserEntity()->getName(),
                'link' => $challenge_user->getUserEntity()->getLink(),
                'url' => $challenge_user->getUserEntity()->getUrl(),
                'image_url' => $challenge_user->getUserEntity()->getImageUrl(),
            ];
        }

        return $ret;
    }

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
     * @Column(name="name", type="text", nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @Column(name="description", type="text", nullable=false)
     */
    private $description;

    /**
     * @var string
     *
     * @Column(name="youtube_video_id", type="string", length=45, nullable=true)
     */
    private $youtubeVideoId;

    /**
     * @var \DateTime
     *
     * @Column(name="created_date", type="datetime", nullable=false)
     */
    private $createdDate;

    /**
     * @var \DateTime
     *
     * @Column(name="updated_date", type="datetime", nullable=true)
     */
    private $updatedDate;

    /**
     * @var User
     *
     * @ManyToOne(targetEntity="User")
     * @JoinColumns({
     *   @JoinColumn(name="from_user_id", referencedColumnName="id")
     * })
     */
    private $fromUser;

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
     * @Column(name="dedication", type="text", nullable=true)
     */
    private $dedication;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param \Entity\Charity $charity
     */
    public function setCharity(Charity $charity)
    {
        $this->charity = $charity;
    }

    /**
     * @return \Entity\Charity
     */
    public function getCharity()
    {
        return $this->charity;
    }

    /**
     * @param \DateTime $createdDate
     */
    public function setCreatedDate(\DateTime $createdDate)
    {
        $this->createdDate = $createdDate;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedDate()
    {
        return $this->createdDate;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }


    /**
     * @param array $options
     *
     * @return string
     */
    public function getDescription($options = [])
    {
        if (isset($options['remove_ending_dot']) && $options['remove_ending_dot']) {
            return rtrim($this->description, '. ');
        } else {
            return $this->description;
        }
    }

    /**
     * @param \Entity\User $fromUser
     */
    public function setFromUser(User $fromUser)
    {
        $this->fromUser = $fromUser;
    }

    /**
     * @return \Entity\User
     */
    public function getFromUser()
    {
        return $this->fromUser;
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

    public function getNameWithChallenge() {
        if (preg_match('#challenge#i', $this->name)) {
            return $this->name;
        } else {
            return $this->name . '-CHALLENGE';
        }
    }

    /**
     * @param \DateTime $updatedDate
     */
    public function setUpdatedDate(\DateTime $updatedDate)
    {
        $this->updatedDate = $updatedDate;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedDate()
    {
        return $this->updatedDate;
    }

    /**
     * @param string $youtubeVideoId
     */
    public function setYoutubeVideoId($youtubeVideoId)
    {
        $this->youtubeVideoId = $youtubeVideoId;
    }

    /**
     * @return string
     */
    public function getYoutubeVideoId()
    {
        return $this->youtubeVideoId;
    }

    public function setFriendsFromPost(array $emails) {
        /** @var ChallengeUser[] $existing */
        $existing = ChallengeUser::findBy(['challenge' => $this]);
        $existing_entities = [];
        foreach($existing as $entity) {
            $existing_entities[$entity->getEmail()] = $entity;
        }

        $new_entities = [];
        foreach($emails as $email) {

            $exists = User::findOneBy(['email' => $email]);
            if ($exists) {
                $new_entities[$email] = $exists;
            } else {
                $new_entities[$email] = $email;
            }
        }

        $em = \Base_Controller::$em;

        foreach($existing_entities as $key => $existing_entity) {
            if (!isset($new_entities[$key])) {
                $em->remove($existing_entity);
                $em->flush();
            }
        }

        foreach($new_entities as $key => $new_entity) {
            if (!isset($existing_entities[$key])) {
                $new_challenge_user = new ChallengeUser();
                if ($new_entity instanceof User) {
                    $new_challenge_user->setUser($new_entity);
                }
                $new_challenge_user->setEmail($key);
                $new_challenge_user->setChallenge($this);
                $em->persist($new_challenge_user);
                $em->flush();
            }
        }
    }

    public function isDraft() {
        foreach($this->getChallengeUsers() as $user) {
            if ($user->getStatus() != 'draft') {
                return false;
            }
        }

        return true;
    }

    /**
     * @return ChallengeUser[]
     */
    public function getChallengeUsers() {
        return ChallengeUser::findBy(['challenge' => $this]);
    }

    /**
     * @return bool
     */
    public function send() {
        if (!$this->isDraft()) {
            return false;
        }

        $em = \Base_Controller::$em;
        foreach($this->getChallengeUsers() as $challenge_user) {
            $challenge_user->send();
        }

        $this->setUpdatedDate(new \DateTime());
        $em->persist($this);
        $em->flush($this);
        return true;
    }

    /**
     * @param User $user
     *
     * @return bool
     */
    public function isToUser(User $user) {
        foreach($this->getChallengeUsers() as $challenge_user) {
            if ($challenge_user->getUserEntity() == $user) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param User $user
     *
     * @return ChallengeUser
     * @throws \Exception
     */
    public function getToChallengeUser(User $user) {
        foreach($this->getChallengeUsers() as $challenge_user) {
            if ($challenge_user->getUserEntity() == $user) {
                return $challenge_user;
            }
        }

        throw new \Exception('The user provided is not one of the users that this challenge is made to. provided user-id: ' . $user->getId() . ' challenge-id: ' . $this->id);
    }

    public function getUserStatus(User $user) {
        foreach($this->getChallengeUsers() as $challenge_user) {
            if ($challenge_user->getUserEntity() == $user) {
                return $challenge_user->getStatus();
            }
        }

        return null;
    }

    public function setUserStatus(User $user, $status) {
        if (!$this->isToUser($user)) {
            throw new \Exception('trying to set user status to ' . $status . ' for challenge failed because user is not receiver of challenge. challenge_id: ' . $this->id . ' user-id: ' . $user->getId());
        }

        foreach($this->getChallengeUsers() as $challenge_user) {
            if ($challenge_user->getUserEntity() == $user) {
                $challenge_user->setStatus($status);
                \Base_Controller::$em->persist($challenge_user);
                \Base_Controller::$em->flush($challenge_user);
                $this->setUpdatedDate(new \DateTime());
                \Base_Controller::$em->persist($this);
                \Base_Controller::$em->flush($this);
                return true;
            }
        }

        throw new \Exception('this should never happen. challenge_id: ' . $this->id . ' user-id: ' . $user->getId() . ' status: ' . $status);
    }

    public function accept_reject(User $user, $accept) {
        $this->setUserStatus($user,$accept ? 'accepted' : 'rejected');
    }
    public function accept(User $user) {
        $this->accept_reject($user,true);
    }
    public function reject(User $user) {
        $this->accept_reject($user,false);
    }

    public function getFullUrl() {
        return $this->getUrl(true);
    }

    public function getUrl($full = true) {
        $relative = '/challenge/'.$this->id;
        if ($full) {
            return base_url($relative);
        } else {
            return $relative;
        }
    }

    public function getLink($full = true) {
        return '<a href="'.$this->getUrl($full).'" title="'.htmlspecialchars($this->getNameWithChallenge()).'">'.htmlspecialchars($this->getNameWithChallenge()).'</a>';
    }

    /**
     * @param string $dedication
     */
    public function setDedication($dedication)
    {
        $this->dedication = $dedication;
    }

    /**
     * @return string
     */
    public function getDedication()
    {
        return $this->dedication;
    }
}
