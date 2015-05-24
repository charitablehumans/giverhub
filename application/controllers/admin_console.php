<?php
require_once(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'libraries' . DIRECTORY_SEPARATOR . 'Nearsoft-SeleniumClient' . DIRECTORY_SEPARATOR . 'SeleniumClient' . DIRECTORY_SEPARATOR . 'WebDriver.php');
require_once(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'libraries' . DIRECTORY_SEPARATOR . 'Nearsoft-SeleniumClient' . DIRECTORY_SEPARATOR . 'SeleniumClient' . DIRECTORY_SEPARATOR . 'By.php');
require_once(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'libraries' . DIRECTORY_SEPARATOR . 'Nearsoft-SeleniumClient' . DIRECTORY_SEPARATOR . 'SeleniumClient' . DIRECTORY_SEPARATOR . 'WebDriverWait.php');
require_once(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'libraries' . DIRECTORY_SEPARATOR . 'Nearsoft-SeleniumClient' . DIRECTORY_SEPARATOR . 'SeleniumClient' . DIRECTORY_SEPARATOR . 'DesiredCapabilities.php');
require_once(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'libraries' . DIRECTORY_SEPARATOR . 'Nearsoft-SeleniumClient' . DIRECTORY_SEPARATOR . 'SeleniumClient' . DIRECTORY_SEPARATOR . 'Exceptions.php');
require_once(__DIR__ . '/Base_Controller.php');

use Entity\User;

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * Class Admin_console
 *
 * intended to be run from the cli
 * http://ellislab.com/codeigniter/user-guide/general/cli.html
 */
class Admin_console extends \Base_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->input->is_cli_request()) {
			echo "You have no permission to access this page";exit;
        }
    }

    public function index() {
        echo 'updateUser username password [capabilities]' . PHP_EOL;
        echo 'createUser email password [capabilities]' . PHP_EOL;
    }
    
	public function updateUser($username = "",$password = "",$capabilities = "confirmed")
	{
        $user = \Entity\User::findOneBy(array('username' => $username));
        if($user) {
            if(trim($password) == "") {
                echo "Password can not be null";
            } else {
                $user->setCapabilities($capabilities);
                $user->setPassword($user->encryptPassword($password));
                $user->setPasswordChanged(date('Y-m-d H:i:s'));
                \Base_Controller::$em->persist($user);
                \Base_Controller::$em->flush($user);
            }
        } else {
            echo "User not found";
        }
	}


    public function createUser($email = "", $password = "", $capabilities = "confirmed")
    {
        if ($email != "" && $password != "") {
            $user = User::findOneBy(array('email' => $email));
            if ($user) {
                echo $email.": user already exist! Please choose another one";
            } else {
                $newUser = new User();

                preg_match('#^(.*)\@#', $email, $matches);
                $newUser->setUsername(\Common::slug($matches[1]));
                $newUser->setFname('');
                $newUser->setLname('');
                $newUser->setImage('');
                $newUser->setActivationKey('');
                $newUser->setCheckedNotifications(date('Y-m-d H:i:s'));
                $newUser->setNoInstantDonationConfirmationMessage(0);
                $newUser->setHideUnhideDonation(1);
                $newUser->setHideUnhideBadges(0);
                $newUser->setPasswordToken('');
                $newUser->setRetrievePasswordTime(date('Y-m-d H:i:s'));
                $newUser->setEmail($email);
                $newUser->setCapabilities($capabilities);
                $newUser->changePassword($password);
                $newUser->setPromptPickUsername(1);

                try {
                    self::$em->persist($newUser);
                    self::$em->flush($newUser);
                } catch (Exception $e) {
                    echo 'PROBLEM SAVING USER';
                    throw $e;
                }
            }
        } else {
            echo "Invalid arguments supplied: createUser email password [capabilities]";
        }
    }


    public function fix_usernames() {
        foreach(User::findAll() as $user) {
            /** @var User $user */
            $username = \Common::slug($user->getUsername());
            echo $user->getId() . PHP_EOL;
            $x = 0;
            while(1) {
                echo $username . PHP_EOL;
                /** @var User[] $us */
                $us = User::findBy(['username' => $username]);
                if (!$us) {
                    echo 'no users with username.' . PHP_EOL;
                    break;
                }

                $onlyMe = true;
                foreach($us as $u) {
                    if ($u->getId() != $user->getId()) {
                        $onlyMe = false;
                        break;
                    }
                }
                if ($onlyMe) {
                    echo 'nobody else has the username.' . PHP_EOL;
                    break;
                }
                $username = $username . '-' . $x;
            }

            echo 'setting : ' . $username . PHP_EOL;
            $user->setUsername($username);
            self::$em->persist($user);
            self::$em->flush($user);
        }
    }

    public function test_dupefail() {
        $em = \Base_Controller::$em;

        $pet = \Entity\ChangeOrgPetition::findOneBy(['petition_id' => '346075']);
        $em->clear();
        $em->persist($pet);
        $em->flush($pet);
    }
}

