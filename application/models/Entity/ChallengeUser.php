<?php
namespace Entity;

/**
 * ChallengeUser
 *
 * @Table(name="challenge_user", indexes={@Index(name="fk_challenge_user_user_idx", columns={"user_id"}), @Index(name="fk_challenge_user_fb_friend_idx", columns={"fb_user_id"}), @Index(name="fk_challenge_user_challenge_idx", columns={"challenge_id"}), @Index(name="fk_challenge_user_video_idx", columns={"challenge_user_video_id"})})
 * @Entity
 */
class ChallengeUser extends BaseEntity {
    /**
     * @var integer
     *
     * @Column(name="id", type="integer", nullable=false)
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var Challenge
     *
     * @ManyToOne(targetEntity="Challenge")
     * @JoinColumns({
     *   @JoinColumn(name="challenge_id", referencedColumnName="id")
     * })
     */
    private $challenge;

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
     * @var ChallengeUserVideo
     *
     * @ManyToOne(targetEntity="ChallengeUserVideo")
     * @JoinColumns({
     *   @JoinColumn(name="challenge_user_video_id", referencedColumnName="id")
     * })
     */
    private $challengeUserVideo;

    /**
     * @var string
     *
     * @Column(name="status", type="string", nullable=false)
     */
    private $status = 'draft';

    /**
     * @var string
     *
     * @Column(name="email", type="string", nullable=true)
     */
    private $email;

    /**
     * @var \DateTime
     *
     * @Column(name="email_sent", type="datetime", nullable=true)
     */
    private $emailSent;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param Challenge $challenge
     */
    public function setChallenge(Challenge $challenge)
    {
        $this->challenge = $challenge;
    }

    /**
     * @return Challenge
     */
    public function getChallenge()
    {
        return $this->challenge;
    }


    /**
     * @param \Entity\User $user
     */
    public function setUser(User $user)
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
     * @return User
     */
    public function getUserEntity() {
        return $this->user;
    }


    public function getUserId() {
        return $this->getUser()->getId();
    }

    public function getImageUrl() {
        return $this->getUserEntity()->getImageUrl();
    }

    static public $statuses = ['draft','sent','accepted','rejected'];

    /**
     * @param $status
     *
     * @throws \Exception
     */
    public function setStatus($status)
    {
        if (!in_array($status,self::$statuses)) {
            throw new \Exception('Invalid new status: ' . $status . ' needs to be one of: ' . join(',', self::$statuses));
        }
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param \Entity\ChallengeUserVideo $challengeUserVideo
     */
    public function setChallengeUserVideo(ChallengeUserVideo $challengeUserVideo = null)
    {
        $this->challengeUserVideo = $challengeUserVideo;
    }

    /**
     * @return \Entity\ChallengeUserVideo
     */
    public function getChallengeUserVideo()
    {
        return $this->challengeUserVideo;
    }

    public function uploadChallengeUserVideoFromPost() {
        $destination_dir = __DIR__.'/../../../videos/challenge/';
        if (disk_free_space($destination_dir) / 1024 / 1024 < 200) {
            return ['success' => false, 'msg' => 'Internal Server Error. We apologize and we are working on fixing this issue. Please consider using a youtube meanwhile.'];
        }
        if (isset($_FILES) && isset($_FILES['video']) && isset($_FILES['video']['error'])) {
            if ($_FILES['video']['error'] != UPLOAD_ERR_OK) {
                // there's an error with the file.. determine what and return the appropriate message.
                switch($_FILES['video']['error']) {
                    case UPLOAD_ERR_INI_SIZE:
                    case UPLOAD_ERR_FORM_SIZE:
                        return ['success' => false, 'msg' => 'Video is too large.'];
                    case UPLOAD_ERR_PARTIAL:
                        return ['success' => false, 'msg' => 'The file was only partially uploaded.'];
                    case UPLOAD_ERR_NO_FILE:
                        return ['success' => false, 'msg' => 'Something unexpected happen, no file was uploaded.'];
                    case UPLOAD_ERR_NO_TMP_DIR:
                        return ['success' => false, 'msg' => 'Internal server error...'];
                    case UPLOAD_ERR_CANT_WRITE:
                        return ['success' => false, 'msg' => 'Internal server error..'];
                    case UPLOAD_ERR_EXTENSION:
                        return ['success' => false, 'msg' => 'Internal server error.'];
                    default:
                        return ['success' => false, 'msg' => 'Unexpected error.'];
                }
            }
            // so the file is ok .. time to validate it's extension and then move it to its proper place :)

            $original_filename = preg_replace('#[\/\\\]#', '', $_FILES['video']['name']); // remove slashes from filename.. to prevent haxx

            $x = 1;
            do { // generate a filename that does not exist in the filesystem or on the db.
                $destination_filename = $this->challenge->getId() . '_' . $this->id . '_' . ++$x . '_' . $original_filename;
                $destination_full_path = $destination_dir . $destination_filename;
                $existingInDb = ChallengeUserVideo::findBy(['filename' => $destination_filename]);
            } while(file_exists($destination_full_path) || $existingInDb);

            // move the file to its destination.
            if (!@move_uploaded_file($_FILES['video']['tmp_name'], $destination_full_path)) {
                return json_encode(['success' => false, 'msg' => 'Internal server error...']);
            }

            $video = new ChallengeUserVideo();
            $video->setFilename($destination_filename);
            $video->setYoutubeVideoId(null);
            $video->setFiletype($_FILES['video']['type']);

            $this->replaceVideo($video);

            return ['success' => true];
        }

        return ['success' => false, 'msg' => 'Uploading file failed.'];
    }

    public function replaceVideo(ChallengeUserVideo $new) {
        $em = \Base_Controller::$em;

        if ($this->challengeUserVideo) {
            // delete previous video uploaded by this user.
            $old = $this->challengeUserVideo;
            $this->setChallengeUserVideo(null);
            $em->persist($this);
            $em->flush($this);

            $em->remove($old);
            $em->flush();
        }

        $em->persist($new);
        $em->flush($new);
        $this->setChallengeUserVideo($new);
        $em->persist($this);
        $em->flush($this);
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail( $email )
    {
        $this->email = $email;
    }

    /**
     * @return \DateTime
     */
    public function getEmailSent()
    {
        return $this->emailSent;
    }

    /**
     * @param \DateTime $emailSent
     */
    public function setEmailSent( $emailSent )
    {
        $this->emailSent = $emailSent;
    }

    public function send() {
        if ($this->getUser()) {
            $subject = 'You have been CHALLENGED on GiverHub.com';

            $receiver_name = $this->getUser()->getName();
            $from_name = $this->getChallenge()->getFromUser()->getName();
            $challenge_link = $this->getChallenge()->getLink();
            $challenge_url = $this->getChallenge()->getUrl();

            $body = "Hi {$receiver_name}!<br/><br/>
{$from_name} has CHALLENGED you on GiverHub.com!
Go check out the challenge here: {$challenge_link}<br/><br/>
Trouble clicking the above link? Copy and paste the following url into your browser:<br/>
{$challenge_url}<br/><br/>
Good luck!

GiverHub";

            // Always set content-type when sending HTML email
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $headers .= "From: GiverHub <admin@giverhub.com>" . "\r\n";
            mail($this->getUser()->getEmail(), $subject, $body, $headers);
            $this->setEmailSent(new \DateTime());
        } elseif ($this->getEmail()) {
            $subject = 'You have been CHALLENGED on GiverHub.com';


            $from_name = $this->getChallenge()->getFromUser()->getName();
            $challenge_link = $this->getChallenge()->getLink();
            $challenge_url = $this->getChallenge()->getUrl();

            $body = "Hi!<br/><br/>
{$from_name} has CHALLENGED you on GiverHub.com!
Go check out the challenge here: {$challenge_link}<br/><br/>
Trouble clicking the above link? Copy and paste the following url into your browser:<br/>
{$challenge_url}<br/><br/>
Good luck!

GiverHub";

            // Always set content-type when sending HTML email
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $headers .= "From: GiverHub <admin@giverhub.com>" . "\r\n";
            mail($this->getEmail(), $subject, $body, $headers);
            $this->setEmailSent(new \DateTime());
        }
        $this->setStatus('sent');
        \Base_Controller::$em->persist($this);
        \Base_Controller::$em->flush($this);
    }

    public function canBeResent() {
        if (!$this->emailSent) {
            return true;
        }

        $now = new \DateTime();
        if ($now->diff($this->emailSent)->format('%d') > 1) {
            return true;
        }
        return false;
    }
}
