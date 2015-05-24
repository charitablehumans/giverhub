<?php
namespace Entity;


/**
 * ClosedBetaSignup
 *
 * @Table(name="closed_beta_signup")
 * @Entity
 */
class ClosedBetaSignup extends BaseEntity
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
     * @Column(name="email", type="string", nullable=false)
     */
    private $email;

    /**
     * @var string
     *
     * @Column(name="signup_date", type="string", nullable=false)
     */
    private $signup_date;

    /**
     * @var integer
     *
     * @Column(name="approved", type="integer", nullable=false)
     */
    private $approved;

    /**
     * @var integer
     *
     * @Column(name="approved_by", type="integer")
     */
    private $approved_by;

    /**
     * @var string
     *
     * @Column(name="approved_date", type="string")
     */
    private $approved_date;

    /**
     * @param int $approved
     */
    public function setApproved($approved)
    {
        $this->approved = $approved;
    }

    /**
     * @return int
     */
    public function getApproved()
    {
        return $this->approved;
    }

    /**
     * @param User $approvedBy
     */
    public function setApprovedBy(User $approvedBy)
    {
        $this->approved_by = $approvedBy->getId();
    }

    /**
     * @return User|null
     */
    public function getApprovedBy()
    {
        if ($this->approved_by) {
            return \Base_Controller::$em->getRepository('\Entity\User')->find($this->approved_by);
        } else {
            return null;
        }
    }

    /**
     * @param string $approved_date
     */
    public function setApprovedDate($approved_date)
    {
        $this->approved_date = $approved_date;
    }

    /**
     * @return string
     */
    public function getApprovedDate()
    {
        return $this->approved_date;
    }

    /**
     * @param \DateTime $approved_date
     */
    public function setApprovedDateDt(\DateTime $approved_date)
    {
        $this->approved_date = $approved_date->format('Y-m-d H:i:s');
    }

    /**
     * @return \DateTime
     */
    public function getApprovedDateDt()
    {
        if ($this->approved_date) {
            return new \DateTime($this->approved_date);
        } else {
            return null;
        }
    }

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
     * @param string $signup_date
     */
    public function setSignupDate($signup_date)
    {
        $this->signup_date = $signup_date;
    }

    /**
     * @return string
     */
    public function getSignupDate()
    {
        return $this->signup_date;
    }

    /**
     * @param \DateTime $signup_date
     */
    public function setSignupDateDt(\DateTime $signup_date)
    {
        $this->signup_date = $signup_date->format('Y-m-d H:i:s');
    }

    /**
     * @return \DateTime
     */
    public function getSignupDateDt()
    {
        if ($this->signup_date) {
            return new \DateTime($this->signup_date);
        } else {
            return null;
        }
    }

    /**
     * @param array $criteria
     * @param array $orderBy
     *
     * @return ClosedBetaSignup
     */
    static public function findOneBy(array $criteria, array $orderBy = null) {
        return \Base_Controller::$em->getRepository('\Entity\ClosedBetaSignup')->findOneBy($criteria, $orderBy);
    }

    /**
     * @return ClosedBetaSignup[]
     */
    static public function findAll() {
        return \Base_Controller::$em->getRepository('\Entity\ClosedBetaSignup')->findAll();
    }

    /**
     * @param $id
     *
     * @return ClosedBetaSignup|null
     */
    static public function find($id) {
        return \Base_Controller::$em->getRepository('\Entity\ClosedBetaSignup')->find($id);
    }

    public function approve() {
        /** @var \Base_Controller $CI */
        $CI =& get_instance();
        if (!$CI->user) {
            throw new \Exception('need to be signed in.');
        }
        $this->setApproved(1);
        $this->setApprovedBy($CI->user);
        $this->setApprovedDate(date('Y-m-d H:i:s'));


        /*
         *         $new_member_insert_data = array(
            'username' => $username,
            'fname' => $fname,
            'lname' => $lname,
            'email' => $email,
            'password' => $password ? \Entity\User::encryptPassword($password) : '',
            'state' => $state,
            'city' => $city,
            'activation_key' => $activationkey,
            'checkedNotifications' => date('Y-m-d H:i:s'),
        );
         */
        $user = User::findOneBy(array('email' => $this->email));

        $password = bin2hex(openssl_random_pseudo_bytes(6));
        do {
            $activation_key = bin2hex(openssl_random_pseudo_bytes(10));
        } while(User::findOneBy(array('activation_key' => $activation_key)));


        preg_match('#^(.*)\@#', $this->email, $matches);
        $tmp_username = $matches[1];
        $tmp_username = \Common::slug($tmp_username);

        $username = $tmp_username;
        $x = 1;
        while (User::findOneBy(array('username' => $username))) {
            $username = \Common::slug($tmp_username . $x++);
        }


        if (!$user) {
            $user = new User();
            $user->setUsername($username);
            $user->setFname('');
            $user->setLname('');
            $user->setEmail($this->email);
            $user->setImage('');
            $user->setCapabilities('confirmed');
            $user->setNoInstantDonationConfirmationMessage(0);
            $user->setHideUnhideDonation(1);
            $user->setHideUnhideBadges(0);
            $user->setScore(0);
            $user->setActivationKey($activation_key);
            $user->setCheckedNotifications(date('Y-m-d H:i:s'));
            $user->setPasswordToken(null);
        }
        $user->setPassword(User::encryptPassword($password));

        \Base_Controller::$em->persist($user);
        \Base_Controller::$em->flush($user);

        \Base_Controller::$em->persist($this);
        \Base_Controller::$em->flush($this);

        require_once __DIR__.'/../../libraries/mailchimp-mandrill-api/src/Mandrill.php'; //Not required with Composer
        try {
            $mandrill = new \Mandrill('uNvjRnFe5NZK11ahpLBCuA');
            $template_name = 'beta-invite-final';
            $template_content = [
                [
                    'name' => 'gh_email',
                    'content' => $user->getEmail(),
                ],
                [
                    'name' => 'gh_password',
                    'content' => $password,
                ],
            ];
            $message = array(
                /*'html' => '<p>Example HTML content</p>',
                'text' => 'Example text content',
                'subject' => 'example subject',*/
                'from_email' => 'admin@giverhub.com',
                'from_name' => 'GiverHub',
                'to' => array(
                    array(
                        'email' => $this->email,
                        'name' => $this->email,
                        'type' => 'to'
                    )
                ),
                /*'headers' => array('Reply-To' => 'message.reply@example.com'),*/
                'important' => false,
                'track_opens' => null,
                'track_clicks' => null,
                'auto_text' => null,
                'auto_html' => null,
                'inline_css' => null,
                'url_strip_qs' => null,
                'preserve_recipients' => null,
                'view_content_link' => null,
                /*'bcc_address' => 'message.bcc_address@example.com',*/
                'tracking_domain' => null,
                'signing_domain' => null,
                'return_path_domain' => null,
                'merge' => true,
                /*'global_merge_vars' => array(
                    array(
                        'GH_EMAIL' => 'b_'.$user->getEmail(),
                        'GH_PASSWORD' => 'b_'.$password,
                    )
                ),*/
                /*'merge_vars' => array(
                    array(
                        'rcpt' => 'recipient.email@example.com',
                        'vars' => array(
                            array(
                                'name' => 'merge2',
                                'content' => 'merge2 content'
                            )
                        )
                    )
                ),*/
                'tags' => array('beta-approval'),
                /*'subaccount' => 'customer-123',
                'google_analytics_domains' => array('example.com'),
                'google_analytics_campaign' => 'message.from_email@example.com',
                'metadata' => array('website' => 'www.example.com'),*/
                /*'recipient_metadata' => array(
                    array(
                        'rcpt' => 'recipient.email@example.com',
                        'values' => array('user_id' => 123456)
                    )
                ),*/
                /*
                'attachments' => array(
                    array(
                        'type' => 'text/plain',
                        'name' => 'myfile.txt',
                        'content' => 'ZXhhbXBsZSBmaWxl'
                    )
                ),
                'images' => array(
                    array(
                        'type' => 'image/png',
                        'name' => 'IMAGECID',
                        'content' => 'ZXhhbXBsZSBmaWxl'
                    )
                )*/
            );

            $result = $mandrill->messages->sendTemplate($template_name, $template_content, $message, false, null, null);
            //print_r($result);
            /*
            Array
            (
                [0] => Array
                    (
                        [email] => recipient.email@example.com
                        [status] => sent
                        [reject_reason] => hard-bounce
                        [_id] => abc123abc123abc123abc123abc123
                    )

            )
            */
        } catch(\Mandrill_Error $e) {
            $this->setApproved(0);
            \Base_Controller::$em->persist($this);
            \Base_Controller::$em->flush($this);
            // Mandrill errors are thrown as exceptions
            echo 'A mandrill error occurred: ' . get_class($e) . ' - ' . $e->getMessage();
            // A mandrill error occurred: Mandrill_Unknown_Subaccount - No subaccount exists with the id 'customer-123'
            throw $e;
        }
        return true;
    }
}
