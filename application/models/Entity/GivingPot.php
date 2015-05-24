<?php
namespace Entity;

/**
 * GivingPot
 *
 * @Table(name="giving_pot", indexes={@Index(name="fk_giving_pot_user_idx", columns={"user_id"}), @Index(name="fk_giving_pot_cof_idx", columns={"card_on_file_id"})}) 
 * @Entity
 */
class GivingPot extends BaseEntity implements \JsonSerializable
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
     * @Column(name="company_logo", type="string", length=255, nullable=true)
     */
    private $companyLogo;

    /**
     * @var string
     *
     * @Column(name="company_name", type="string", length=255, nullable=true)
     */
    private $companyName;

    /**
     * @var integer
     *
     * @Column(name="pot_size", type="integer", nullable=true)
     */
    private $potSize;

    /**
     * @var string
     *
     * @Column(name="summary", type="string", length=140, nullable=true)
     */
    private $summary;

    /**
     * @var string
     *
     * @Column(name="body", type="text", nullable=true)
     */
    private $body;

    /**
     * @var string
     *
     * @Column(name="button_text", type="string", length=140, nullable=true)
     */
    private $buttonText;

    /**
     * @var string
     *
     * @Column(name="button_url", type="string", length=255, nullable=true)
     */
    private $buttonUrl;

    /**
     * @var integer
     *
     * @Column(name="amount_remaining", type="integer", nullable=true)
     */
    private $amountRemaining;

    /**
     * @var boolean
     *
     * @Column(name="published", type="boolean", nullable=false)
     */
    private $published = '0';

    /**
     * @var CardOnFile
     *
     * @ManyToOne(targetEntity="CardOnFile")
     * @JoinColumns({
     *   @JoinColumn(name="card_on_file_id", referencedColumnName="id")
     * })
     */
    private $cardOnFile;

    /**
     * @var User
     *
     * @ManyToOne(targetEntity="User")
     * @JoinColumns({
     *   @JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;

    public $scope = 'Preview';

    public function getScope() {
        if ($this->isPublished()) {
            return '';
        } else {
            return $this->scope;
        }
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getCompanyLogo()
    {
        return $this->companyLogo;
    }

    /**
     * @param string $companyLogo
     */
    public function setCompanyLogo( $companyLogo )
    {
        $this->companyLogo = $companyLogo;
    }

    /**
     * @return string
     */
    public function getCompanyName()
    {
        return $this->companyName;
    }

    /**
     * @param string $companyName
     */
    public function setCompanyName( $companyName )
    {
        $this->companyName = $companyName;
    }

    /**
     * @return int
     */
    public function getPotSize()
    {
        return $this->potSize;
    }

    /**
     * @param int $potSize
     */
    public function setPotSize( $potSize )
    {
        $this->potSize = $potSize;
    }

    /**
     * @return string
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * @param string $summary
     */
    public function setSummary( $summary )
    {
        $this->summary = $summary;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param string $body
     */
    public function setBody( $body )
    {
        $this->body = $body;
    }

    /**
     * @return string
     */
    public function getButtonText()
    {
        return $this->buttonText;
    }

    /**
     * @param string $buttonText
     */
    public function setButtonText( $buttonText )
    {
        $this->buttonText = $buttonText;
    }

    /**
     * @return string
     */
    public function getButtonUrl()
    {
        return $this->buttonUrl;
    }

    /**
     * @param string $buttonUrl
     */
    public function setButtonUrl( $buttonUrl )
    {
        $this->buttonUrl = $buttonUrl;
    }

    /**
     * @return int
     */
    public function getAmountRemaining()
    {
        return $this->amountRemaining;
    }

    /**
     * @param int $amountRemaining
     */
    public function setAmountRemaining( $amountRemaining )
    {
        $this->amountRemaining = $amountRemaining;
    }

    /**
     * @return boolean
     */
    public function isPublished()
    {
        return $this->published;
    }

    /**
     * @param boolean $published
     */
    public function setPublished( $published )
    {
        $this->published = $published;
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

    public function render() {
        require_once(__DIR__. '/../../libraries/lightncandy.php');
        $template_location = __DIR__.'/../../../assets/scripts/handlebars-templates/giving-pot/giving_pot.handlebars';

        $renderer_location = $template_location.'-'.md5_file($template_location).'.php';

        if (!is_file($renderer_location)) {
            $template = file_get_contents( $template_location );
            $phpStr   = \LightnCandy::compile( $template );
            file_put_contents( $renderer_location, $phpStr );
        }
        /** @var callable $renderer */
        $renderer = include($renderer_location);
        return $renderer($this->toArray());
    }

    public function toArray() {
        return [
            'companyLogo' => $this->companyLogo,
            'companyName' => $this->companyName,
            'potSize' => $this->potSize,
            'amountRemaining' => $this->amountRemaining,
            'scope' => $this->getScope(),
            'summary' => $this->summary,
            'body' => nl2br(htmlspecialchars($this->body)),  // we escape first, and then we add newlines!
            'buttonUrl' => $this->buttonUrl,
            'buttonText' => $this->buttonText,
        ];
    }

    public function jsonSerialize() {
        return $this->toArray();
    }

    public function deleteCompanyLogo()
    {
        $full_path = __DIR__ . '/../../../' . $this->companyLogo;
        if (file_exists( $full_path )) {
            @unlink( $full_path );
        }
        $this->companyLogo = null;
        \Base_Controller::$em->persist( $this );
        \Base_Controller::$em->flush($this);
    }

    public $errors = [];

    public function validate() {
        $this->errors = [];
        if (!$this->companyLogo && !$this->companyName) {
            $this->errors['companyName'] = [
                'label' => 'Company logo/name',
                'msg' => 'Company logo or name must be set.'
            ];
        }

        if ($this->potSize < 10) {
            $this->errors['potSize'] = [
                'label' => 'Pot Size',
                'msg' => 'Pot Size must be a digit. Minimum 10.',
            ];
        }

        if (strlen($this->summary) > 140 || !strlen($this->summary)) {
            $this->errors['summary'] = [
                'label' => 'Summary',
                'msg' => 'Summary is required. Max 140 characters.',
            ];
        }

        if (!strlen($this->body)) {
            $this->errors['body'] = [
                'label' => 'Body',
                'msg' => 'Body is required.'
            ];
        }

        if (!strlen($this->buttonText)) {
            $this->errors['buttonText'] = [
                'label' => 'Button Text',
                'msg' => 'Button Text is required.'
            ];
        }

        if (!strlen($this->buttonUrl)) {
            $this->errors['buttonUrl'] = [
                'label' => 'Button Url',
                'msg' => 'Button Url is required.'
            ];
        }

        if (!preg_match('#http:\/\/|https:\/\/#', $this->buttonUrl) || !filter_var($this->buttonUrl, FILTER_VALIDATE_URL)) {
            $this->errors['buttonUrl'] = [
                'label' => 'Button Url',
                'msg' => 'Button Url is invalid. Must be a valid url starting with http:// or https://',
            ];
        }

        if (!$this->getCardOnFile()) {
            $this->errors['paymentMethod'] = [
                'label' => 'Payment Method',
                'msg' => 'Payment Method is missing. Please select a payment method',
            ];
        }

        return !$this->errors;
    }

    public function saveFromPost($post, $validate = true) {
        if ($post['companyName']) {
            $this->companyName = $post['companyName'];
            $this->deleteCompanyLogo();
        } else {
            $this->companyName = null;
        }
        if ($post['companyLogo']) {
            $this->companyName = null;
            $this->companyLogo = $post['companyLogo'];
        } else {
            $this->companyLogo = null;
        }

        if ($post['potSize']) {
            $this->potSize = $post['potSize'];
            $this->amountRemaining = $post['potSize'];
        } else {
            $this->potSize = null;
            $this->amountRemaining = null;
        }

        if ($post['summary']) {
            $this->summary = $post['summary'];
        } else {
            $this->summary = null;
        }

        if ($post['body']) {
            $this->body = $post['body'];
        } else {
            $this->body = null;
        }

        if ($post['buttonText']) {
            $this->buttonText = $post['buttonText'];
        } else {
            $this->buttonText = null;
        }

        if ($post['buttonUrl']) {
            $this->buttonUrl = $post['buttonUrl'];
        } else {
            $this->buttonUrl = null;
        }

        if ($validate) {
            if (!$this->validate()) {
                return false;
            }
        }

        \Base_Controller::$em->persist($this);
        \Base_Controller::$em->flush($this);

        return true;
    }

    /**
     * @return CardOnFile
     */
    public function getCardOnFile()
    {
        return $this->cardOnFile;
    }

    /**
     * @param CardOnFile $cardOnFile
     */
    public function setCardOnFile(CardOnFile $cardOnFile)
    {
        $this->cardOnFile = $cardOnFile;
    }

    public function addRecipients($recipients) {
        if (!is_array($recipients)) {
            throw new \Exception('recipients must be an array');
        }

        if (!$recipients) {
            throw new \Exception('recipients cannot be empty.');
        }

        $CI =& get_instance();
        $CI->load->config('mailsvariation');

        foreach($recipients as $nr => $recipient) {
            foreach(['name','email','amount'] as $required_field) {
                if (!isset($recipient[$required_field]) || !$recipient[$required_field]) {
                    throw new \Exception($required_field . ' is missing from recipient nr: ' . $nr);
                }
            }

            $name = $recipient['name'];
            $email = $recipient['email'];
            $amount = $recipient['amount'];

            $user = User::findOneBy(['email' => $email]);

            $givercard = new GivercardTransactions();
            $givercard->setCofId($this->getCardOnFile()->getCofId());
            $givercard->setAmount($amount);
            $givercard->setBalanceAmount($amount);
            $givercard->setDateCreated(date('Y-m-d H:i:s'));
            $givercard->setFromUserId($this->getUser());
            if ($user) {
                $givercard->setToUser($user);
            } else {
                $givercard->setToEmail($email);
            }

            $givercard->setMessage($this->getSummary());

            $em = \Base_Controller::$em;
            $em->persist($givercard);
            $em->flush($givercard);

            $givercard_id = $givercard->getId();

            $from = $CI->config->item('from');
            $companyname = $CI->config->item('companyname');
            $fromUserFullName = $this->getUser()->getName();
            $subject = str_replace("[user_x]",$fromUserFullName,$CI->config->item('givercard_receiver_subject'));
            $baseUrl = $CI->config->item('base_url');
            $mailHeader = "<img src='".$baseUrl."/images/logo_plus_givercard.png' width='100%' height='120px'>";
            $viewYourGiverCard = '<a href="'.$baseUrl.'giver-cards/view_givercard/'.$givercard_id.'">Your GiverCard</a>';

            $temp1 = array("[givercard_mail_header]",
                           "[user_y]",
                           "[user_x]",
                           '[name_or_logo]',
                           "[givercard_amount]",
                           "[num_of_nonprofits]",
                           "[user_x_fname]",
                           "[givercard_message]",
                           "[view_givercard_link]"
            );

            $temp2 = array($mailHeader,
                           $name,
                           $this->getUser()->getName(),
                           ($this->companyName ? $this->companyName : '<img src="'.$baseUrl.$this->companyLogo.'" height="20">'),
                           $amount,
                           "2 millions",
                           $this->getUser()->getFname(),
                           $this->summary,
                           $viewYourGiverCard);

            $body = str_replace($temp1,$temp2,$CI->config->item('giving_pot_recipient_email_body'));

            // Always set content-type when sending HTML email
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $headers .= "From: GiverHub <admin@giverhub.com>" . "\r\n";

            mail($email, $subject, $body, $headers);


            $pot_recipient = new GivingPotRecipient();
            $pot_recipient->setGivingPot($this);
            $pot_recipient->setGivercard($givercard);
            $pot_recipient->setName($name);

            $em->persist($pot_recipient);
            $em->flush($pot_recipient);

            $this->amountRemaining -= $amount;
            $em->persist($this);
            $em->flush($this);
        }

    }

    /** @var GivingPotRecipient[] */
    private $_recipients;

    /**
     * @return GivingPotRecipient[]
     */
    public function getRecipients() {
        if ($this->_recipients === null) {
            $this->_recipients = GivingPotRecipient::findBy( [ 'givingPot' => $this ] );
        }
        return $this->_recipients;
    }
}