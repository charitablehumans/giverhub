<?php
use \Entity\User;
use \Entity\Charity;

require_once(__DIR__ . '/Base_Controller.php');


class Bet extends Base_Controller
{

    public function __construct() {
        parent::__construct();
    }

    public function index($id) {
        /** @var \Entity\Bet $bet */
        $bet = \Entity\Bet::find($id);
        if (!$bet) {
            $this->giverhub_404('nonprofits/charity-404', 'Bet Not Found');
            return;
        }

        $data['bet'] = $bet;
        $data['main_content'] = 'bets/facebook_og_page';

        $this->headerPrefix = 'og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# giverhub: http://ogp.me/ns/fb/giverhub#';
        $this->ogTitle = $bet->getName();
        $this->ogType = 'giverhub:bet';
        $this->ogDesc = $bet->getTerms(['remove_ending_dot' => true]) . ".\n\nGiverHub: Donate instantly, itemize automatically.";
        $this->ogImage = [(@$_SERVER['SERVER_PORT'] == 443 ? 'https' : 'http') . '://' . @$_SERVER['SERVER_NAME'] . '/assets/images/bet-a-friend-facebook-share3.png'];

        $this->extra_headers[] = '<meta property="og:amount" content="$'.$bet->getAmount().'">';

        $this->load->view('includes/user/template', $data);
    }

    public function save_for_later() {
        if (!$this->user) {
            throw new Exception('user is not signed in!');
        }

        if (!isset($_POST['name'])) {
            throw new Exception('name is missing!');
        }
        if (!strlen($_POST['name'])) {
            throw new Exception('name is too short! should have been validated by client side js. name: ' . $_POST['name']);
        }

        if (!isset($_POST['terms'])) {
            throw new Exception('terms is missing!');
        }
        if (strlen($_POST['terms']) < 5) {
            throw new Exception('terms is too short. should have been validated by client side javascript. terms: ' . $_POST['terms']);
        }

        if (!isset($_POST['amount'])) {
            throw new Exception('amount is missing!');
        }
        if ($_POST['amount'] < 10) {
            throw new Exception('amount is under 10. amount: ' . $_POST['amount']);
        }

        if (!isset($_POST['date'])) {
            throw new Exception('date is missing!');
        }
        $date = DateTime::createFromFormat('m/d/y', $_POST['date']);
        if ($date === false) {
            throw new Exception('date is invalid. date: ' . $_POST['date']);
        }

        if (!isset($_POST['charity_id'])) {
            throw new Exception('charity_id is missing.');
        }
        /** @var \Entity\Charity $charity */
        $charity = Charity::find($_POST['charity_id']);
        if (!$charity) {
            throw new Exception('charity_id could not be found in db. charity_id: ' . $_POST['charity_id']);
        }

        if (!isset($_POST['bet-a-friend-page'])) {
            throw new Exception('bet-a-friend-page must be set to 1 or 0 .. It will be used to determine what type of html to return');
        }

        if (isset($_POST['bet_id'])) {
            /** @var \Entity\Bet $bet */
            $bet = \Entity\Bet::find($_POST['bet_id']);
            if (!$bet) {
                throw new Exception('Could not load bet. bet_id: ' . $_POST['bet_id']);
            }
            if ($bet->getUser() != $this->user) {
                throw new Exception('bet is not owned by current user. user_id: ' . $bet->getUser()->getId() . ' current-user-id: ' . $this->user->getId());
            }
        } else {
            $bet = new \Entity\Bet();
            $bet->setCreatedDate(new DateTime());
        }

        $bet->setName($_POST['name']);
        $bet->setTerms($_POST['terms']);
        $bet->setAmount($_POST['amount']);
        $bet->setUser($this->user);

        $bet->setDeterminationDate($date);
        $bet->setCharity($charity);
        $bet->setStatus('draft');
        self::$em->persist($bet);
        self::$em->flush($bet);

        echo json_encode([
            'success' => true,
            'bet' => $bet,
            'bet_list' => $this->load->view('/bets/_bet-list', null, true),
        ]);
    }

    public function send() {
        if (!$this->user) {
            throw new Exception('user is not signed.');
        }

        if (!isset($_POST['bet_id'])) {
            throw new Exception('missing bet_id');
        }

        /** @var \Entity\Bet|null $bet */
        $bet = \Entity\Bet::find($_POST['bet_id']);

        if (!$bet) {
            throw new Exception('could not open bet. bet_id: ' . $_POST['bet_id']);
        }

        if ($bet->getUser() != $this->user) {
            throw new Exception('bet does not belong to signed in user. bet_id: ' . $_POST['bet_id'] . ' first_user_id: ' . $bet->getUser()->getId() . ' signed_user_id: ' . $this->user->getId());
        }

        if ($bet->getStatus() != 'draft') {
            throw new Exception('Bet needs to have draft status when sending it. bet-id: ' . $bet->getId() . ' status: ' . $bet->getStatus());
        }

        if (!isset($_POST['open'])) {
            throw new Exception('missing open.');
        }

        if (!in_array($_POST['open'], ['1', '0'])) {
            throw new Exception('Open is invalid. should be 1 or 0');
        }

        if (isset($_POST['friends']) && is_array($_POST['friends'])) {
            foreach($_POST['friends'] as $friend) {
                if (!isset($friend['facebook_friend'])) {
                    throw new Exception('Invalid friend. missing facebook_friend');
                }
                if (!isset($friend['friend_id'])) {
                    throw new Exception('Invalid friend. missing friend_id');
                }

                $bet_friend = new \Entity\BetFriend();
                $bet_friend->setBet($bet);
                if ($friend['facebook_friend']) {
                    $friend_ent = \Entity\FacebookFriend::find($friend['friend_id']);
                } else {
                    $friend_ent = \Entity\User::find($friend['friend_id']);
                }
                if (!$friend_ent) {
                    throw new Exception('Could not load friend entity. facebook_friend: ' . $friend['facebook_friend'] . ' friend_id: ' . $friend['friend_id']);
                }
                $bet_friend->setFriend($friend_ent);

                self::$em->persist($bet_friend);
                self::$em->flush($bet_friend);
            }
        }

        if (isset($_POST['emails']) && is_array($_POST['emails'])) {
            foreach($_POST['emails'] as $email) {
                $bet_friend = new \Entity\BetFriend();
                $bet_friend->setBet($bet);
                $user = \Entity\User::findOneBy(['email' => $email]);
                if ($user) {
                    $bet_friend->setUser($user);
                }
                $bet_friend->setEmail($email);

                $body =
                    htmlspecialchars($this->user->getName()) . " wants to make a bet with you for charity on GiverHub! ".
                    htmlspecialchars($this->user->getFname()). " wants to bet you $".$bet->getAmount()." that ".
                    htmlspecialchars($bet->getTerms(['remove_ending_dot' => true])).
                    ". If you win the bet you get to determine which charity the their money goes to! To accept or reject the bet, <a href=\"".$bet->getUrl(true)."\">go here.</a><br/><br/>
<b>What is GiverHub?<br/><br>
GiverHub is the easiest, fastest, and funnest way to discover, learn about, and donate to ANY nonprofit, and it automatically itemizes all of your past and recurring donations for you in a donation history.</b><br/><br/>
But GiverHub is also much more than that. It enables you to search for change.org petitions that suit your interests and sign them instantly, all without ever leaving GiverHub! You can create Challenges, or send GiverCard charity gift cards. It's a centralized hub for all your online giving. Where you go to do good! But it’s also a social network for givers that enables them to communicate, collaborate, and compete with (just for fun of course ;) ) like-minded users to do more good. And this is all just the beginning for GiverHub. We’ve got so many more features in the pipeline that aren’t just going to change the way you give back, they’re going to change the way you THINK about giving back. Thank you for being a Giver!<br/><br/>

Andrew Levine, President of GiverHub

";

                // Always set content-type when sending HTML email
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                $headers .= "From: GiverHub <admin@giverhub.com>" . "\r\n";
                mail($email, htmlspecialchars($this->user->getFname()) . " is betting you on GiverHub", $body, $headers);

                self::$em->persist($bet_friend);
                self::$em->flush($bet_friend);
            }
        }

        $bet->setOpen($_POST['open']);

        $bet->setStatus('sent');

        self::$em->persist($bet);
        self::$em->flush($bet);

        echo json_encode([
                'success' => true,
                'bet_list' => $this->load->view('/bets/_bet-list', null, true),
            ]);
    }

    public function reject() {
        if (!$this->user) {
            throw new Exception('user is not signed.');
        }

        if (!isset($_POST['bet_id'])) {
            throw new Exception('missing bet_id');
        }

        /** @var \Entity\Bet|null $bet */
        $bet = \Entity\Bet::find($_POST['bet_id']);

        if (!$bet) {
            throw new Exception('could not open bet. bet_id: ' . $_POST['bet_id']);
        }

        if (!$bet->isToUser($this->user)) {
            throw new Exception('bet cannot be accepted by signed in user. bet_id: ' . $_POST['bet_id'] . ' signed_user_id: ' . $this->user->getId());
        }

        $friend = $bet->getFriend($this->user);

        if ($friend->getStatus() != 'pending') {
            throw new Exception('BetFriend needs to have pending status when rejecting it. friend-id: ' . $friend->getId() . ' status: ' . $friend->getStatus());
        }
        if ($bet->getStatus() != 'sent') {
            throw new Exception('Bet needs to have sent status when accepting it. bet-id: ' . $bet->getId() . ' status: ' . $bet->getStatus());
        }

        $friend->setStatus('rejected');

        self::$em->persist($friend);
        self::$em->flush($friend);

        echo json_encode([
                'success' => true,
                'bet_info' => $this->load->view('bets/_bet_info', ['bet' => $bet], true),
                'bet_list' => $this->load->view('bets/_bet-list', null, true),
            ]);
    }

    public function accept() {
        if (!$this->user) {
            throw new Exception('user is not signed.');
        }

        if (!isset($_POST['bet_id'])) {
            throw new Exception('missing bet_id');
        }

        /** @var \Entity\Bet|null $bet */
        $bet = \Entity\Bet::find($_POST['bet_id']);

        if (!$bet) {
            throw new Exception('could not open bet. bet_id: ' . $_POST['bet_id']);
        }

        if (!$bet->isToUser($this->user)) {
            throw new Exception('bet cannot be accepted by signed in user. bet_id: ' . $_POST['bet_id'] . ' signed_user_id: ' . $this->user->getId());
        }

        $friend = $bet->getFriend($this->user);

        if ($friend->getStatus() != 'pending') {
            throw new Exception('Bet Friend needs to have pending status when accepting it. friend-id: ' . $friend->getId() . ' status: ' . $friend->getStatus());
        }
        if ($bet->getStatus() != 'sent') {
            throw new Exception('Bet needs to have sent status when accepting it. bet-id: ' . $bet->getId() . ' status: ' . $bet->getStatus());
        }

        $friend->setStatus('incomplete');

        self::$em->persist($friend);
        self::$em->flush($friend);

        echo json_encode([
                'success' => true,
                'bet_info' => $this->load->view('bets/_bet_info', ['bet' => $bet], true),
                'bet_list' => $this->load->view('bets/_bet-list', null, true),
                'bet' => $bet,
            ]);
    }

    public function choose_charity() {
        if (!$this->user) {
            throw new Exception('user is not signed.');
        }

        if (!isset($_POST['bet_id'])) {
            throw new Exception('missing bet_id');
        }

        /** @var \Entity\Bet|null $bet */
        $bet = \Entity\Bet::find($_POST['bet_id']);

        if (!$bet) {
            throw new Exception('could not open bet. bet_id: ' . $_POST['bet_id']);
        }

        if (!$bet->isToUser($this->user)) {
            throw new Exception('bet cannot be accepted by signed in user. bet_id: ' . $_POST['bet_id'] . ' signed_user_id: ' . $this->user->getId());
        }

        $friend = $bet->getFriend($this->user);


        if (!isset($_POST['charity_id'])) {
            throw new Exception('charity_id is missing.');
        }
        /** @var \Entity\Charity $charity */
        $charity = Charity::find($_POST['charity_id']);
        if (!$charity) {
            throw new Exception('Could not open charity, charity_id: ' . $_POST['charity_id']);
        }

        if ($friend->getStatus() != 'incomplete') {
            throw new Exception('BetFriend needs to have incomplete status when choosing charity for it. friend-id: ' . $friend->getId() . ' status: ' . $friend->getStatus());
        }

        if ($friend->getCharity()) {
            throw new Exception('charity has already been set.');
        }
        $friend->setCharity($charity);
        $friend->setStatus('accepted');

        self::$em->persist($friend);
        self::$em->flush($friend);

        echo json_encode([
                'success' => true,
                'bet_info' => $this->load->view('bets/_bet_info', ['bet' => $bet], true),
                'bet_list' => $this->load->view('bets/_bet-list', null, true),
            ]);
    }

    public function delete_draft() {
        if (!$this->user) {
            throw new Exception('user is not signed.');
        }

        if (!isset($_POST['bet_id'])) {
            throw new Exception('missing bet_id');
        }

        /** @var \Entity\Bet|null $bet */
        $bet = \Entity\Bet::find($_POST['bet_id']);

        if (!$bet) {
            throw new Exception('could not open bet. bet_id: ' . $_POST['bet_id']);
        }

        if ($bet->getUser() != $this->user) {
            throw new Exception('bet can only be deleted by signed in user. bet_id: ' . $_POST['bet_id'] . ' first_user_id: ' . $bet->getUser()->getId() . ' signed_user_id: ' . $this->user->getId());
        }

        if ($bet->getStatus() != 'draft') {
            throw new Exception('Bet needs to have draft status when deleting it. bet-id: ' . $bet->getId() . ' status: ' . $bet->getStatus());
        }

        self::$em->remove($bet);
        self::$em->flush();

        echo json_encode(['success' => true, 'bet_list' => $this->load->view('bets/_bet-list', null, true)]);
    }

    public function claim_win() {
        if (!$this->user) {
            throw new Exception('user is not signed.');
        }

        if (!isset($_POST['bet_id'])) {
            throw new Exception('missing bet_id');
        }

        /** @var \Entity\Bet|null $bet */
        $bet = \Entity\Bet::find($_POST['bet_id']);

        if (!$bet) {
            throw new Exception('could not open bet. bet_id: ' . $_POST['bet_id']);
        }

        if ($bet->getStatus() != 'sent') {
            throw new Exception('Bet needs to have sent status when claiming win for it. bet-id: ' . $bet->getId() . ' status: ' . $bet->getStatus());
        }

        if ($bet->getUser() == $this->user) {
            $bet->setClaim('win');
            self::$em->persist($bet);
            self::$em->flush($bet);
        } else {
            $friend = $bet->getFriend($this->user);

            if ($friend->getStatus() != 'accepted') {
                throw new Exception('BetFriend needs to have accepted before claiming a win. bet-friend-id: ' . $friend->getId() . ' status: ' . $friend->getStatus());
            }

            $friend->setClaim('win');
            self::$em->persist($friend);
            self::$em->flush($friend);
        }

        echo json_encode([
                'success' => true,
                'bet_info' => $this->load->view('bets/_bet_info', ['bet' => $bet], true),
                'bet_list' => $this->load->view('bets/_bet-list', null, true),
            ]);
    }

    public function claim_loss() {
        if (!$this->user) {
            throw new Exception('user is not signed.');
        }

        if (!isset($_POST['bet_id'])) {
            throw new Exception('missing bet_id');
        }

        /** @var \Entity\Bet|null $bet */
        $bet = \Entity\Bet::find($_POST['bet_id']);

        if (!$bet) {
            throw new Exception('could not open bet. bet_id: ' . $_POST['bet_id']);
        }

        if ($bet->getStatus() != 'sent') {
            throw new Exception('Bet needs to have sent status when claiming loss for it. bet-id: ' . $bet->getId() . ' status: ' . $bet->getStatus());
        }

        if ($bet->getUser() == $this->user) {
            $bet->setClaim('loss');
            self::$em->persist($bet);
            self::$em->flush($bet);
        } else {
            $friend = $bet->getFriend($this->user);

            if ($friend->getStatus() != 'accepted') {
                throw new Exception('BetFriend needs to have accepted before claiming a loss. bet-friend-id: ' . $friend->getId() . ' status: ' . $friend->getStatus());
            }

            $friend->setClaim('loss');
            self::$em->persist($friend);
            self::$em->flush($friend);
        }


        echo json_encode([
                'success' => true,
                'bet_info' => $this->load->view('bets/_bet_info', ['bet' => $bet], true),
                'bet_list' => $this->load->view('bets/_bet-list', null, true),
                'bet' => $bet,
            ]);
    }

	/** accept an open bet.. (join the bet) */
	public function accept_open() {
		if (!$this->user) {
			throw new Exception('user not signed in');
		}
		if (!isset($_POST['bet_id'])) {
			throw new Exception('missing bet_id');
		}

		/** @var \Entity\Bet|null $bet */
		$bet = \Entity\Bet::find($_POST['bet_id']);
		if (!$bet) {
			throw new Exception('bet could not be found. bet-id: ' . $_POST['bet_id']);
		}

		if (!$bet->isOpen()) {
			throw new Exception('Bet is not open');
		}

		if ($bet->getUser() == $this->user || $bet->isToUser($this->user)) {
			throw new Exception('User is already in bet.. user-id: ' . $this->user->getId() . ' bet-id: ' . $bet->getId());
		}

		$friend = new \Entity\BetFriend();
		$friend->setBet($bet);
		$friend->setUser($this->user);
		$friend->setStatus('requested');

		self::$em->persist($friend);
		self::$em->flush($friend);

		// HACK: reload to include the newly added friend...
		$bet = \Entity\Bet::find($_POST['bet_id']);

		$subject = 'Someone wants to take your bet on GiverHub.com';

		$from_name = $this->user->getName();
		$receiver_name = $bet->getUser()->getName();
		$bet_link = $bet->getLink();
		$bet_url = $bet->getUrl();

		$body = "Hi {$receiver_name}!<br/><br/>
{$from_name} has REQUESTED to accept your bet on GiverHub.com!
Go check out and accept the bet here: {$bet_link}<br/><br/>
Trouble clicking the above link? Copy and paste the following url into your browser:<br/>
{$bet_url}<br/><br/>
Good luck!

GiverHub";

		// Always set content-type when sending HTML email
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
		$headers .= "From: GiverHub <admin@giverhub.com>" . "\r\n";
		mail($bet->getUser()->getEmail(), $subject, $body, $headers);

		echo json_encode([
			'success' => true,
			'bet_info' => $this->load->view('bets/_bet_info', ['bet' => $bet], true),
			'bet_list' => $this->load->view('bets/_bet-list', null, true),
		]);
	}


	public function accept_request() {
		if (!$this->user) {
			throw new Exception('user not signed in');
		}
		if (!isset($_POST['friend_id'])) {
			throw new Exception('missing friend_id');
		}

		/** @var \Entity\BetFriend|null $friend */
		$friend = \Entity\BetFriend::find($_POST['friend_id']);
		if (!$friend) {
			throw new Exception('friend could not be found. friend-id: ' . $_POST['friend_id']);
		}

		if (!$friend->getStatus() == 'requested') {
			throw new Exception('Friend status is wrong. friend-id: ' . $friend->getId() . ' status: ' . $friend->getStatus());
		}

		if ($friend->getBet()->getUser() != $this->user) {
			throw new Exception('Bet is not from current user. bet-id: ' . $friend->getBet()->getId() . ' user-id: ' . $this->user->getId());
		}

		$friend->setStatus('incomplete');

		self::$em->persist($friend);
		self::$em->flush($friend);


		$subject = 'Your request to join a bet on GiverHub.com has been accepted!';

		$from_name = $this->user->getName();
		$receiver_name = $friend->getUser()->getName();
		$bet_link = $friend->getBet()->getLink();
		$bet_url = $friend->getBet()->getUrl();

		$body = "Hi {$receiver_name}!<br/><br/>
{$from_name} has ACCEPTED your request to join a bet on GiverHub.com!
Go check out the bet here: {$bet_link}<br/>
And don't forget to choose your nonprofit!<br/><br/>
Trouble clicking the above link? Copy and paste the following url into your browser:<br/>
{$bet_url}<br/><br/>
Good luck!

GiverHub";

		// Always set content-type when sending HTML email
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
		$headers .= "From: GiverHub <admin@giverhub.com>" . "\r\n";
		mail($friend->getUser()->getEmail(), $subject, $body, $headers);


		echo json_encode([
			'success' => true,
			'bet_info' => $this->load->view('bets/_bet_info', ['bet' => $friend->getBet()], true),
			'bet_list' => $this->load->view('bets/_bet-list', null, true),
		]);
	}

	public function reject_request() {
		if (!$this->user) {
			throw new Exception('user not signed in');
		}
		if (!isset($_POST['friend_id'])) {
			throw new Exception('missing friend_id');
		}

		/** @var \Entity\BetFriend|null $friend */
		$friend = \Entity\BetFriend::find($_POST['friend_id']);
		if (!$friend) {
			throw new Exception('friend could not be found. friend-id: ' . $_POST['friend_id']);
		}

		if (!$friend->getStatus() == 'requested') {
			throw new Exception('Friend status is wrong. friend-id: ' . $friend->getId() . ' status: ' . $friend->getStatus());
		}

		if ($friend->getBet()->getUser() != $this->user) {
			throw new Exception('Bet is not from current user. bet-id: ' . $friend->getBet()->getId() . ' user-id: ' . $this->user->getId());
		}

		$friend->setStatus('request_rejected');

		self::$em->persist($friend);
		self::$em->flush($friend);

		$subject = 'Your request to join a bet on GiverHub.com has been rejected!';

		$from_name = $this->user->getName();
		$receiver_name = $friend->getUser()->getName();
		$bet_link = $friend->getBet()->getLink();
		$bet_url = $friend->getBet()->getUrl();

		$body = "Hi {$receiver_name}!<br/><br/>
{$from_name} has REJECTED your request to join a bet on GiverHub.com!
Go check out the bet here: {$bet_link}<br/><br/>
Trouble clicking the above link? Copy and paste the following url into your browser:<br/>
{$bet_url}<br/><br/>
Sorry!

GiverHub";

		// Always set content-type when sending HTML email
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
		$headers .= "From: GiverHub <admin@giverhub.com>" . "\r\n";
		mail($friend->getUser()->getEmail(), $subject, $body, $headers);

		echo json_encode([
			'success' => true,
			'bet_info' => $this->load->view('bets/_bet_info', ['bet' => $friend->getBet()], true),
			'bet_list' => $this->load->view('bets/_bet-list', null, true),
		]);
	}
}
