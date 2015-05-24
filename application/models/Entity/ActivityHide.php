<?php
namespace Entity;

use SeleniumClient\EmptyValueException;

/**
 * ActivityHide
 *
 * @Table(name="activity_hide", uniqueConstraints={@UniqueConstraint(name="uq_activity_hide", columns={"user_id", "activity_id", "activity_type"})}, indexes={@Index(name="IDX_A75D10B9A76ED395", columns={"user_id"})})
 * @Entity
 */
class ActivityHide extends BaseEntity {

    static $types = [
        'challenge',
        'charity-change',
        'charity-follower',
        'charity-review',
        'donation',
        'facebook-like',
        'co-pet-facebook-share',
        'gh-pet-facebook-share',
        'co-pet-signature',
        'gh-pet-signature',
        'user-follower',
    ];

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
     * @Column(name="activity_id", type="integer", nullable=false)
     */
    private $activityId;

    /**
     * @var string
     *
     * @Column(name="activity_type", type="string", nullable=false)
     */
    private $activityType;

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
    public function getActivityId()
    {
        return $this->activityId;
    }

    /**
     * @param int $activityId
     */
    public function setActivityId( $activityId )
    {
        $this->activityId = $activityId;
    }

    /**
     * @return string
     */
    public function getActivityType()
    {
        return $this->activityType;
    }

    /**
     * @param $activityType
     *
     * @throws \Exception
     */
    public function setActivityType( $activityType )
    {
        if (!in_array($activityType, self::$types)) {
            throw new \Exception('invalid type: ' . $activityType . ' should be one of: ' . join(', ', self::$types));
        }
        $this->activityType = $activityType;
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

    static public function getHtml($id, $type) {
        switch($type) {
            case 'challenge':
                $activity = Challenge::find($id);
                break;
            case 'charity-change':
                $activity = CharityChangeHistory::find($id);
                break;
            case 'charity-follower':
                $activity = CharityFollower::find($id);
                break;
            case 'charity-review':
                $activity = CharityReview::find($id);
                break;
            case 'donation':
                $activity = Donation::find($id);
                break;
            case 'facebook-like':
                $activity = FacebookLike::find($id);
                break;
            case 'co-pet-facebook-share':
                $activity = ChangeOrgPetitionFacebookShare::find($id);
                break;
            case 'gh-pet-facebook-share':
                $activity = PetitionFacebookShare::find($id);
                break;
            case 'co-pet-signature':
                $activity = UserPetitionSignature::find($id);
                break;
            case 'gh-pet-signature':
                $activity = PetitionSignature::find($id);
                break;
            case 'user-follower':
                $activity = UserFollower::find($id);
                break;
            default:
                throw new \Exception('Invalid type: ' . $type);
        }

        if (!$activity) {
            throw new \Exception('failed to load activity, id: ' . $id . ' type: ' . $type);
        }
        $CI =& get_instance();
        return $CI->load->view('/members/_activity', ['context'=>'my', 'activity' => $activity], true);
    }
}
