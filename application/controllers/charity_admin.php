<?php

require_once(__DIR__ . '/Base_Controller.php');
require_once __DIR__.'/../libraries/htmlpurifier/HTMLPurifier.auto.php';

use \Entity\Charity;
use \Entity\CharityAdmin;
use \Entity\CharityAdminData;

class Charity_Admin extends Base_Controller {
    public function save() {
        if (!$this->user) {
            throw new Exception('Need to be signed in');
        }
        if (!isset($_POST['charity_id'])) {
            throw new Exception('Missing charity_id');
        }
        /** @var Charity $charity */
        $charity = Charity::find($_POST['charity_id']);

        if (!$charity) {
            throw new Exception('Charity cannot be loaded. charity_id: ' . $_POST['charity_id']);
        }

        if (!$this->user->isCharityAdmin($charity)) {
            throw new Exception('User is not charity admin for charity. user_id: ' . $this->user->getId() . ' charity_id: ' . $charity->getId());
        }

        if (isset($_POST['mission'])) {
            $config = HTMLPurifier_Config::createDefault();
            $purifier = new HTMLPurifier($config);
            $_POST['mission'] = $purifier->purify($_POST['mission']); // blasphemy, right?
        }





        foreach(['tagline', 'mission', 'facebook_page'] as $field) {
            if (!isset($_POST[$field])) {
                continue;
            }
            $entity = CharityAdminData::findOneBy(['charity' => $charity, 'field' => $field]);
            if (!$_POST[$field] && $entity) {
                self::$em->remove($entity);
                self::$em->flush();
            }
            if ($_POST[$field]) {
                if (!$entity) {
                    $entity = new CharityAdminData;
                    $entity->setCharity($charity);
                    $entity->setField($field);
                }
                $entity->setUser($this->user);
                $entity->setValue($_POST[$field]);
                self::$em->persist($entity);
                self::$em->flush($entity);
            }
        }

        echo json_encode(['success' => true]);
    }

    public function save_name_url() {
        if (!$this->user) {
            throw new Exception('user is not signed in.');
        }

        if (!isset($_POST['charity_id'])) {
            throw new Exception('charity_id is missing');
        }

        /** @var \Entity\Charity $charity */
        $charity = \Entity\Charity::find($_POST['charity_id']);

        if (!$charity) {
            throw new Exception('failed loading charity. charity_id: ' . $_POST['charity_id']);
        }

        if (!$this->user->isAdmin() && !$this->user->isCharityAdmin($charity)) {
            throw new Exception('user is not charity admin. user-id: ' . $this->user->getId() . ' charity_id: ' . $_POST['charity_id']);
        }

        if (!isset($_POST['name'])) {
            throw new Exception('name is missing');
        }

        if (!isset($_POST['url'])) {
            throw new Exception('url is missing');
        }

        $json = [];
        $errors = false;
        if (strlen($_POST['name']) < 3) {
            $json['name_error'] = 'Name is too short. Minimum 3 characters.';
            $errors = true;
        }

        $url_slug = \Common::slug($_POST['url']);
        if ($url_slug != $_POST['url']) {
            $json['url_error'] = 'Url is invalid. Use only lowercase letters, digits and -';
            $errors = true;
        }

        if (strlen($url_slug) < 3) {
            $json['url_error'] = 'Url is too short. Minimum 3 characters.';
            $errors = true;
        }

        if ($errors) {
            $json['success'] = false;
            echo json_encode($json);
        } else {
            try {
                $charity->changeUrlSlug($_POST['url']);
                $charity->setName($_POST['name']);
                self::$em->persist($charity);
                self::$em->flush($charity);
                echo json_encode(['success' => true, 'url' => $charity->getUrl()]);
            } catch(\Entity\UrlSlugIsUsedException $e) {
                echo json_encode(['success' => false, 'url_error' => 'Url is being used by another nonprofit.']);
            }
        }
    }
}