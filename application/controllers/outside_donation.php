<?php
require_once(__DIR__ . '/Base_Controller.php');


class Outside_Donation extends Base_Controller {
    public function add() {
        if (!$this->user) {
            throw new Exception('user not signed in.');
        }

        if (!isset($_POST['nonprofit'])) {
            throw new Exception('missing nonprofit name');
        }
        $nonprofit = trim($_POST['nonprofit']);
        if (!strlen($nonprofit)) {
            throw new Exception('nonprofit name is missing.');
        }

        if (!isset($_POST['amount'])) {
            throw new Exception('missing amount');
        }
        $amount = trim($_POST['amount']);
        if (!is_numeric($amount) || $amount <= 0) {
            throw new Exception('invalid amount: ' . $amount);
        }

        if (!isset($_POST['date'])) {
            throw new Exception('missing date');
        }

        $date = DateTime::createFromFormat('Y-m-d', $_POST['date']);
        $date_errors = DateTime::getLastErrors();
        if ($date_errors['warning_count'] + $date_errors['error_count'] > 0) {
            throw new Exception('invalid date: ' . $_POST['date']);
        }

        $time = null;
        if (isset($_POST['time']) && strlen(trim($_POST['time']))) {
            $time = DateTime::createFromFormat('H:i', $_POST['time']);
            $time_errors = DateTime::getLastErrors();
            if ($time_errors['warning_count'] + $time_errors['error_count'] > 0) {
                throw new Exception('invalid time: ' . $_POST['time']);
            }
        }

        $cause = null;
        if (isset($_POST['cause']) && strlen(trim($_POST['cause']))) {
            $cause = $_POST['cause'];
        }

        $outside_donation = new \Entity\OutsideDonation();
        $outside_donation->setUser($this->user);
        $outside_donation->setNonprofitName($nonprofit);
        $outside_donation->setAmount($amount);
        $outside_donation->setDate($date);
        $outside_donation->setTime($time);
        $outside_donation->setCause($cause);

        self::$em->persist($outside_donation);
        self::$em->flush($outside_donation);

        echo json_encode([
            'success' => true,
            'donation_history_html' => $this->load->view('/members/_donation-history-tbody', [
                'simplifiedArray' => $this->user->getDonationHistoryArray()
            ], true)
        ]);
    }
}
