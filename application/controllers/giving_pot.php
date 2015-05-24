<?php
require_once(__DIR__ . '/Base_Controller.php');

require_once(__DIR__. '/../libraries/lightncandy.php');

class Giving_Pot extends Base_Controller {

    public function about() {
        $this->htmlTitle = 'Giving Pots - About';

        $demo = new \Entity\GivingPot;
        $demo->setCompanyLogo('/img/navbar-logo.png');
        $demo->setPotSize(1000);
        $demo->setAmountRemaining(920);
        $demo->setSummary("Win $10 for every $100 donated");
        $demo->setBody("For every donation of over $100 that a GiverHub member makes and shares on facebook, ".
        "they will receive $10 which they can donate to any nonprofit of their choice until the pot runs out. ".
        "So if a user donates $100 to three different nonprofits, ".
        "they will receive $30 which they can donate to any nonprofit or nonprofits. ".
        "If they donate $300 to one nonprofit they will only receive $10. ".
        "Once the original $1,000 has been used up the promotion is over! ".
        "To take advantage of this promotion, simply donate over $100 to any nonprofit anywhere on GiverHub!");

        $demo->setButtonText('START DONATING');
        $demo->scope = 'Sample Giving Pot';

        $data['giving_pot'] = $demo;

        $data['main_content'] = 'giving-pot/about';
        $this->load->view('includes/user/template', $data);
    }

    public function create() {
        if (!$this->user) {
            redirect('/?redirect=/giving-pot/create');
        }

        $giving_pot = new \Entity\GivingPot();
        $giving_pot->setUser($this->user);
        self::$em->persist($giving_pot);
        self::$em->flush($giving_pot);

        redirect('/giving-pot/edit/'.$giving_pot->getId());
    }

    public function edit($id) {
        if (!$this->user) {
            redirect('/?redirect=/giving-pot/edit/'.$id);
        }
        /** @var \Entity\GivingPot $giving_pot */
        $giving_pot = \Entity\GivingPot::find($id);
        if ($giving_pot->getUser() != $this->user) {
            redirect('/');
        }

        if ($giving_pot->isPublished()) {
            redirect('/giving-pot/dashboard/'.$id);
        }

        $this->htmlTitle = 'Giving Pot';

        $data['giving_pot'] = $giving_pot;

        $data['main_content'] = 'giving-pot/edit';
        $this->load->view('includes/user/template', $data);
    }

    public function saveDraft($id) {
        if (!$this->user) {
            throw new Exception('user not signed in when saving giving pot');
        }

        /** @var \Entity\GivingPot $giving_pot */
        $giving_pot = \Entity\GivingPot::find($id);
        if (!$giving_pot) {
            throw new Exception('could not load giving-pot:' . $id);
        }

        if ($giving_pot->getUser() != $this->user) {
            throw new Exception('giving pot does not belong to user. pot-id: ' . $id. ' user-id: ' . $this->user->getId());
        }

        if ($giving_pot->isPublished()) {
            throw new Exception('pot is already published: ' . $id);
        }

        if (!isset($_POST['pot'])) {
            throw new Exception('pot is missing');
        }

        $giving_pot->saveFromPost($_POST['pot'], false);
    }

    public function publish($id) {
        if (!$this->user) {
            throw new Exception('user not signed in when saving giving pot');
        }

        /** @var \Entity\GivingPot $giving_pot */
        $giving_pot = \Entity\GivingPot::find($id);
        if (!$giving_pot) {
            throw new Exception('could not load giving-pot:' . $id);
        }

        if ($giving_pot->getUser() != $this->user) {
            throw new Exception('giving pot does not belong to user. pot-id: ' . $id. ' user-id: ' . $this->user->getId());
        }

        if ($giving_pot->isPublished()) {
            throw new Exception('pot is already published: ' . $id);
        }

        if (!isset($_POST['pot'])) {
            throw new Exception('pot is missing');
        }

        if (!$giving_pot->saveFromPost($_POST['pot'], true)) {
            echo json_encode(['success' => true, 'errors' => $giving_pot->errors]);
        } else {
            $giving_pot->setPublished(1);
            self::$em->persist($giving_pot);
            self::$em->flush($giving_pot);

            echo json_encode(['success' => true]);
        }
    }

    public function dashboard($id) {
        if (!$this->user) {
            redirect('/?redirect=/giving-pot/dashboard/'.$id);
        }
        /** @var \Entity\GivingPot $giving_pot */
        $giving_pot = \Entity\GivingPot::find($id);
        if ($giving_pot->getUser() != $this->user) {
            redirect('/');
        }

        if (!$giving_pot->isPublished()) {
            redirect('/giving-pot/edit/'.$id);
        }

        $this->htmlTitle = 'Giving Pot - Dashboard';

        $data['giving_pot'] = $giving_pot;

        $data['main_content'] = 'giving-pot/dashboard';
        $this->load->view('includes/user/template', $data);
    }

    public function set_payment_method($id) {
        if (!$this->user) {
            throw new Exception('user not signed in when setting payment method giving pot');
        }

        /** @var \Entity\GivingPot $giving_pot */
        $giving_pot = \Entity\GivingPot::find($id);
        if (!$giving_pot) {
            throw new Exception('could not load giving-pot:' . $id);
        }

        if ($giving_pot->getUser() != $this->user) {
            throw new Exception('giving pot does not belong to user. pot-id: ' . $id. ' user-id: ' . $this->user->getId());
        }

        if ($giving_pot->isPublished()) {
            throw new Exception('pot is already published: ' . $id);
        }

        if (!isset($_POST['card'])) {
            throw new Exception('card is missing');
        }

        $cardOnFile = \Entity\CardOnFile::getOrCreateFromPost($_POST['card']);

        $giving_pot->setCardOnFile($cardOnFile);
        self::$em->persist($giving_pot);
        self::$em->flush($giving_pot);

        echo json_encode([
            'success' => true,
            'payment_method_html' => $this->load->view('/giving-pot/_payment-method', ['giving_pot' => $giving_pot], true)
        ]);
    }

    public function add_recipients($id) {
        if (!$this->user) {
            throw new Exception('not signed in');
        }
        /** @var \Entity\GivingPot $giving_pot */
        $giving_pot = \Entity\GivingPot::find($id);
        if ($giving_pot->getUser() != $this->user) {
            throw new Exception('user does not own giving-pot: pot-id: ' . $id . ' user-id: ' . $this->user->getId());
        }

        if (!$giving_pot->isPublished()) {
            throw new Exception('giving pot is not published. id: ' . $id);
        }


        foreach(['name', 'amount', 'email'] as $required) {
            if (!isset($_POST[$required])) {
                throw new Exception('missing: ' . $required);
            }
            if (!is_array($_POST[$required])) {
                throw new Exception($required . ' is not an array');
            }
            if (!$_POST[$required]) {
                throw new Exception($required . ' is empty.');
            }
        }

        if (count($_POST['name']) != count($_POST['amount']) ||  count($_POST['amount']) != count($_POST['email'])) {
            throw new Exception('count mismatch..');
        }

        $errors = [];
        $recipients = [];
        $total_amount = 0;
        foreach($_POST['name'] as $k => $name) {
            $name = trim($name);
            $email = trim($_POST['email'][$k]);
            $amount = trim($_POST['amount'][$k]);

            if (!$name && !$email && !$amount) {
                // totally empty line, skip! continue ;)
                continue;
            }

            $recipients[] = [
                'name' => $name,
                'email' => $email,
                'amount' => $amount,
            ];

            // validation time
            if (!$name) {
                $errors[$k]['name'] = 'Name is missing';
            }
            if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[$k]['email'] = 'Email is invalid';
            }
            if (!$amount || $amount < 10) {
                $errors[$k]['amount'] = 'Must be $10 or more.';
            }
            $total_amount += (int)$amount;
        }

        if ($total_amount > $giving_pot->getAmountRemaining()) {
            echo json_encode([
                'success' => true,
                'error_msg' => 'The total amount exceeds the giving pots remaining balance. You tried to send $'.$total_amount . ' but the remaining balance is only $' . $giving_pot->getAmountRemaining(),
            ]);
            return;
        }

        if ($errors) {
            echo json_encode(['success' => true, 'errors' => $errors]);
            return;
        }

        if (!$recipients) {
            echo json_encode(['success' => true, 'no_recipients' => true]);
            return;
        }

        $giving_pot->addRecipients($recipients);

        echo json_encode([
            'success' => true,
            'pot' => $giving_pot,
            'existing_recipients_html' => $this->load->view('/giving-pot/_existing_recipients', ['giving_pot' => $giving_pot], true),
        ]);
    }
}
