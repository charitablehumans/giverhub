<?php
class FakeUser {
    public $email;

    public function __construct($email) {
        $this->email = $email;
    }

    public function getLink() {
        return '<a href="mailto:'.$this->email.'">'.$this->email.'</a>';
    }

    public function getFname() {
        return $this->email;
    }

    public function getUrl() {
        return 'mailto:'.$this->email;
    }

    public function getImageUrl() {
        return '/images/user-avatar-default.png';
    }

    public function getName() {
        return $this->getFname();
    }
}