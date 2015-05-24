<?php
namespace Entity;

/**
 * CharityVolunteeringOpportunity
 *
 * @Table(name="charity_volunteering_opportunity", indexes={@Index(name="fk_charity_volunteering_opportunity_user_idx", columns={"user_id"}), @Index(name="fk_charity_volunteering_opportunity_charity_idx", columns={"charity_id"})})
 * @Entity @HasLifecycleCallbacks
 */
class CharityVolunteeringOpportunity extends BaseEntity implements \JsonSerializable {

    /** @PrePersist */
    public function onPrePersist()
    {
        $this->created = new \DateTime();
    }


    /** @PreUpdate */
    public function onPreUpdate()
    {
        $this->updated = new \DateTime();
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
     * @Column(name="title", type="string", length=40, nullable=false)
     */
    private $title;

    /**
     * @var string
     *
     * @Column(name="description", type="text", nullable=false)
     */
    private $description;

    /**
     * @var string
     *
     * @Column(name="location", type="text", nullable=false)
     */
    private $location;

    /**
     * @var string
     *
     * @Column(name="occurs", type="string", nullable=false)
     */
    private $occurs = 'once';
    static public $occurs_types = ['once', 'daily', 'weekly', 'monthly', 'yearly'];

    /**
     * @var string
     *
     * @Column(name="time_zone", type="string", nullable=true)
     */
    private $timeZone;

    static public $time_zones = [
        'UTC-10:00' => 'HST / Hawaii Time / UTC-10:00',
        'UTC-09:00' => 'AKDT / Alaska Time / UTC-09:00',
        'UTC-08:00' => 'PDT / Pacific Time / UTC-08:00',
        'UTC-07:00' => 'MDT / Mountain Time / UTC-07:00',
        'UTC-06:00' => 'CDT / Central Time / UTC-06:00',
        'UTC-05:00' => 'EDT / Eastern Time / UTC-05:00',
    ];

    static public $date_time_zones = [
        'UTC-10:00' => -10,
        'UTC-09:00' => -9,
        'UTC-08:00' => -8,
        'UTC-07:00' => -7,
        'UTC-06:00' => -6,
        'UTC-05:00' => -5
    ];

    static public $php_time_zones = [
        'UTC-10:00' => 'America/Adak',
        'UTC-09:00' => 'America/Anchorage',
        'UTC-08:00' => 'America/Los_Angeles',
        'UTC-07:00' => 'America/Denver',
        'UTC-06:00' => 'America/Chicago',
        'UTC-05:00' => 'America/New_York',
    ];

    static public $php_time_zones_reverse = [
        'America/Adak' => 'UTC-10:00',
        'America/Anchorage' => 'UTC-09:00',
        'America/Los_Angeles' => 'UTC-08:00',
        'America/Denver' => 'UTC-07:00',
        'America/Chicago' => 'UTC-06:00',
        'America/New_York' => 'UTC-05:00',
    ];

    /**
     * @var \DateTime
     *
     * @Column(name="start_date", type="date", nullable=false)
     */
    private $start_date;

    /**
     * @var \DateTime
     *
     * @Column(name="end_date", type="date", nullable=true)
     */
    private $end_date;

    /**
     * @var \DateTime
     *
     * @Column(name="start_time", type="time", nullable=true)
     */
    private $startTime;

    /**
     * @var \DateTime
     *
     * @Column(name="end_time", type="time", nullable=true)
     */
    private $endTime;

    /**
     * @var \DateTime
     *
     * @Column(name="created", type="datetime", nullable=false)
     */
    private $created;

    /**
     * @var \DateTime
     *
     * @Column(name="updated", type="datetime", nullable=true)
     */
    private $updated;

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
     * @var User
     *
     * @ManyToOne(targetEntity="User")
     * @JoinColumns({
     *   @JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;

    /**
     * @param \Entity\Charity $charity
     */
    public function setCharity($charity)
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
     * @param \DateTime $created
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }

    /**
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param \DateTime $date
     */
    public function setStartDate($date)
    {
        $this->start_date = $date;
    }

    /**
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->start_date;
    }

    /**
     * @param \DateTime $date
     */
    public function setEndDate($date)
    {
        $this->end_date = $date;
    }

    /**
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->end_date;
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
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $location
     */
    public function setLocation($location)
    {
        $this->location = $location;
    }

    /**
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param $occurs
     *
     * @throws \Exception
     */
    public function setOccurs($occurs)
    {
        if (!in_array($occurs, self::$occurs_types)) {
            throw new \Exception('invalid occurs_type: ' . $occurs . ' should be one of ' . join(', ', self::$occurs_types));
        }
        $this->occurs = $occurs;
    }

    /**
     * @return string
     */
    public function getOccurs()
    {
        return $this->occurs;
    }

    /**
     * @param $timeZone
     *
     * @throws \Exception
     */
    public function setTimeZone($timeZone)
    {
        if ($timeZone !== null && !in_array($timeZone, array_keys(self::$time_zones))) {
            throw new \Exception('invalid time_zone: ' . $timeZone. ' should be one of ' . join(', ', self::$time_zones));
        }
        $this->timeZone = $timeZone;
    }

    /**
     * @return string
     */
    public function getTimeZone()
    {
        return $this->timeZone;
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
     * @param \DateTime $updated
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
    }

    /**
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * @param \Entity\User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return \Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param \DateTime $endTime
     */
    public function setEndTime($endTime)
    {
        $this->endTime = $endTime;
    }

    /**
     * @return \DateTime
     */
    public function getEndTime()
    {
        return $this->endTime;
    }

    /**
     * @param \DateTime $startTime
     */
    public function setStartTime($startTime)
    {
        $this->startTime = $startTime;
    }

    /**
     * @return \DateTime
     */
    public function getStartTime()
    {
        return $this->startTime;
    }

    public function getDateString() {
        return $this->occurs === 'once' ? $this->start_date->format('F jS') : ucfirst($this->occurs);
    }

    public function isAllDay() {
        return !$this->startTime && !$this->endTime;
    }

    public function getTimeString() {
        return $this->isAllDay() ? 'All Day' : ($this->startTime->format('g:ia').'-'.$this->endTime->format('g:ia'));
    }

    public function getOccursString() {
        return 'This opportunity occurs ' . ucfirst($this->occurs);
    }

    public function getNextTimeStamp() {
        switch($this->occurs) {
            case 'once':
                $dt = clone $this->start_date;
                break;
            case 'daily':
                $dt = new \DateTime();
                break;
            case 'weekly':
                $dt = new \DateTime('this ' . $this->start_date->format('l'));  // eg "this saturday"
                break;
            case 'monthly':
                $dt = new \DateTime('first day of this month');
                $month = $dt->format('m');
                $dt->modify('+'.($this->start_date->format('d')-1).' day'); // add n days to beginning of month.. where n is the start dates day -1
                if ($dt->format('m') != $month) {
                    // we went too far.. set to last day of this month..
                    $dt = new \DateTime('last day of this month');
                }
                break;
            case 'yearly':
                // take todays date ..
                $dt = new \DateTime();
                // keep the year.. use start-times month and day
                $dt->setDate($dt->format('Y'), $this->start_date->format('m'), $this->start_date->format('d'));
                break;
            default:
                throw new \Exception('occurs is invalid: ' . $this->occurs);
        }



        if ($this->isAllDay()) {
            $dt->setTime(9, 0, 0);
        } else {
            $dt->setTime($this->startTime->format('H'), $this->startTime->format('i'), $this->startTime->format('s'));
        }

        if ($this->timeZone === null) {
            return $dt->getTimestamp();
        } else {
            return $dt->getTimestamp() - (self::$date_time_zones[$this->timeZone]*60*60);
        }
    }

    public function hasSignedInUserLiked() {
        if (!\Base_Controller::$staticUser) {
            return false;
        }
        return \Base_Controller::$staticUser&&ActivityFeedPostLike::didUserLikeIt(\Base_Controller::$staticUser, $this);
    }

    public function getCalendarEvent($timezone = 'UTC-05:00') {
        $start = clone $this->start_date;
        if ($this->startTime) {
            $hour = $this->startTime->format('H');
            if ($timezone && $this->timeZone) {
                $hour += self::$date_time_zones[$timezone] - self::$date_time_zones[$this->timeZone];
            }
            $start->setTime($hour,$this->startTime->format('i'));
        }

        if ($this->end_date) {
            $end = clone $this->end_date;
            if ($this->endTime) {
                $end->setTime($this->endTime->format('H'),$this->endTime->format('i'));
            }
        }

        $event = [
            'id' => $this->id,
            'title' => $this->title,
            'start' => $this->startTime ? $start->format(DATE_ATOM) : $start->format('Y-m-d'),
            'url' => '/volunteering-opportunities/'.$this->getCharity()->getUrlSlug(),
        ];

        if (isset($end)) {
            $event['end'] = $this->endTime ? $end->format(DATE_ATOM) : $end->format('Y-m-d');
        }

        return $event;
    }

    public function getCalendarEvents() {
        return [$this->getCalendarEvent()];

        if ($this->getOccurs() == 'once') {
            return [$this->getCalendarEvent()];
        } else {
            $cal_event = $this->getCalendarEvent();

        }
    }

    public function jsonSerialize() {
        $arr = [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'location' => $this->location,
            'start_date' => $this->start_date->format('Y-m-d'),
            'end_date' => $this->end_date ? $this->end_date->format('Y-m-d') : null,
            'start_time' => $this->startTime ? $this->startTime->format('g:iA') : null,
            'end_time' => $this->endTime ? $this->endTime->format('g:iA') : null,
            'all_day' => $this->isAllDay(),
            'occurs' => $this->occurs,
            'time_zone' => $this->timeZone === null ? null : [
                'id' => $this->timeZone,
                'name' => self::$time_zones[$this->timeZone]
            ],
            'date_string' => $this->getDateString(),
            'time_string' => $this->getTimeString(),
            'occurs_string' => $this->getOccursString(),
            'next_timestamp' => $this->getNextTimeStamp(),
            'likes' => ActivityFeedPostLike::getLikes($this),
            'hasSignedInUserLiked' => $this->hasSignedInUserLiked(),
            'comments' => ActivityFeedPostComment::getComments($this),
            'cal_events' => $this->getCalendarEvents(),
        ];
        return $arr;
    }
}
