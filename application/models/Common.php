<?php

use \Entity\User;

class Common {

    static public function create_member_fb($data, Doctrine\ORM\EntityManager $em) {

        $userRepo = $em->getRepository('Entity\User');

        /** @var Entity\User|null $user */
        if ($user = $userRepo->findOneBy(array('fb_user_id' => $data['id']))) {
            return $user;
        }

        if ($user = $userRepo->findOneBy(array('email' => $data['email']))) {
            $user->setFbUserId($data['id']);
            $user->fixBets();
            $user->fixChallenges();
			$user->fixFacebookGivercards();
            $em->persist($user);
            $em->flush($user);
            return $user;
        }


        $email = $data['email'];

        preg_match('#^(.*)\@#', $email, $matches);
        $tmp_username = $matches[1];
        $tmp_username = self::slug($tmp_username);

        $username = $tmp_username;
        $x = 1;
        while ($userRepo->findOneBy(array('username' => $username))) {
            $username = self::slug($tmp_username . $x++);
        }


        $nameParts = explode(' ', $data['name']);

        $fName = $nameParts[0];
        $lName = $nameParts[1] . (isset($nameParts[2]) ? ' ' . $nameParts[2] : '');
        $email = $data['email'];

        $newUser = new User();

        $newUser->setUsername($username);
        $newUser->setFname($fName);
        $newUser->setLname($lName);
        $newUser->setImage('');
        $newUser->setActivationKey('');
        $newUser->setCheckedNotifications(date('Y-m-d H:i:s'));
        $newUser->setNoInstantDonationConfirmationMessage(0);
        $newUser->setHideUnhideDonation(1);
        $newUser->setHideUnhideBadges(0);
        $newUser->setPasswordToken('');
        $newUser->setRetrievePasswordTime(date('Y-m-d H:i:s'));
        $newUser->setEmail($email);
        $newUser->setCapabilities('confirmed');
        $newUser->setPassword('');
        $newUser->setFbUserId($data['id']);
		$newUser->setPromptPickUsername(1);

        $CI =& get_instance();
        $last_request_uri = $CI->session->userdata('prev_request_uri');
        $newUser->setUrlBeforeSignup($last_request_uri);
        try {
            \Base_Controller::$em->persist($newUser);
            \Base_Controller::$em->flush($newUser);

            $newUser->fixBets();
            $newUser->fixChallenges();
            $newUser->fixFacebookGivercards();


            $CI->session->set_flashdata('event_signed_up', 'facebook');

            return $newUser;
        } catch (Exception $e) {
            return 'Something went wrong when creating your account. We apologize. Please Try again.' . $e->getMessage() . print_r($data,true);
        }

    }


    static public function create_member_google($data) {
        $CI =& get_instance();

        $em = \Base_Controller::$em;
        $userRepo = $em->getRepository('Entity\User');
        /** @var Entity\User|null $user  */
        if ($user = $userRepo->findOneBy(array('google_user_id' => $data['id']))) {
            return $user;
        }

        /** @var Entity\User|null $user  */
        if ($user = $userRepo->findOneBy(array('email' => $data['email']))) {
            $user->setGoogleUserId($data['id']);
            $em->persist($user);
            $em->flush($user);
            $CI->session->set_flashdata('event_signed_up', 'google');
            return $user;
        }

        if (CLOSED_BETA) {
            $signup = \Entity\ClosedBetaSignup::findOneBy(array('email' => $data['email']));
            if (!$signup) {
                $signup = new \Entity\ClosedBetaSignup();
                $signup->setEmail($data['email']);
                $signup->setApproved(0);
                $signup->setSignupDate(date('Y-m-d H:i:s'));
                \Base_Controller::$em->persist($signup);
                \Base_Controller::$em->flush($signup);
                $CI->session->unset_userdata('google_access_token');
                return 'We have signed you up for our beta wait list. We will send you an email with instructions once we approve your beta membership.';
            } elseif (!$signup->getApproved()) {
                $CI->session->unset_userdata('google_access_token');
                return 'Your email address is already signed up to the beta membership wait list. We will send you an email with instructions once we approve your beta membership.';
            }
        }

        preg_match('#^(.*)\@#', $data['email'], $matches);
        $tmp_username = $matches[1];
        $tmp_username = self::slug($tmp_username);

        $username = $tmp_username;
        $x = 1;
        while ($userRepo->findOneBy(array('username' => $username))) {
            $username = self::slug($tmp_username . $x++);
        }

        $newUser = new User();

        $newUser->setUsername($username);
        $newUser->setFname($data['given_name']);
        $newUser->setLname($data['family_name']);
        $newUser->setImage('');
        $newUser->setActivationKey('');
        $newUser->setCheckedNotifications(date('Y-m-d H:i:s'));
        $newUser->setNoInstantDonationConfirmationMessage(0);
        $newUser->setHideUnhideDonation(1);
        $newUser->setHideUnhideBadges(0);
        $newUser->setPasswordToken('');
        $newUser->setRetrievePasswordTime(date('Y-m-d H:i:s'));
        $newUser->setEmail($data['email']);
        $newUser->setCapabilities('confirmed');
        $newUser->setPassword('');
        $newUser->setGoogleUserId($data['id']);
		$newUser->setPromptPickUsername(1);

        $CI =& get_instance();
        $last_request_uri = $CI->session->userdata('prev_request_uri');
        $newUser->setUrlBeforeSignup($last_request_uri);

        try {
            \Base_Controller::$em->persist($newUser);
            \Base_Controller::$em->flush($newUser);

            $newUser->fixChallenges();
            $newUser->fixBets();

            $CI =& get_instance();
            $CI->session->set_flashdata('event_signed_up', 'google');

            return $newUser;
        } catch (Exception $e) {
            return false;
        }

    }

    static public function create_member() {
        $CI =& get_instance();


        $CI->load->config('mailsvariation');
        $username = $CI->input->post('username') ? self::slug($CI->input->post('username')) : "";
        if (@$_POST['name']) {
            $pieces = explode(' ', $_POST['name']);
            $fname = array_shift($pieces);
            $lname = join(' ', $pieces);
        } else {
            $fname = $CI->input->post('fname') ? $CI->input->post('fname') : "";
            $lname = $CI->input->post('lname') ? $CI->input->post('lname') : "";
        }
        $email = $CI->input->post('email') ? $CI->input->post('email') : "";

        $password = $CI->input->post('password') ? $CI->input->post('password') : "";

        //generating truely unique activation key
        $CI->db->select('activation_key');
        $keyarray = $CI->db->get('users');
        $activationKey = generaterandomkey($keyarray->result());

        // getting config variable for email
        $from = $CI->config->item('from');
        $companyname = $CI->config->item('companyname');
        $to = $email;

        //replaceing shortcodes [] with the actual content
        $subject = str_replace('[company name]', $companyname, $CI->config->item('register_subject'));
        $temp = str_replace('[name]', $fname . ' ' . $lname, $CI->config->item('register_body'));
        $temp = str_replace('[company name]', $companyname, $temp);
        $link = '<a href="' . base_url() . 'register/activate_account/' . $activationKey . '">' . base_url() . 'register/activate_account/' . $activationKey . '</a>';
        $linktext = base_url() . 'register/activate_account/' . $activationKey;
        $temp = str_replace('[link]', $link, $temp);
        $body = str_replace('[Text]', $linktext, $temp);

        //Sending verification email
        emailsending($from, $to, $subject, $body, $companyname, 1);


        $newUser = new User();

        $newUser->setUsername($username);
        $newUser->setFname($fname);
        $newUser->setLname($lname);
        $newUser->setImage('');
        $newUser->setActivationKey($activationKey);
        $newUser->setCheckedNotifications(date('Y-m-d H:i:s'));
        $newUser->setNoInstantDonationConfirmationMessage(0);
        $newUser->setHideUnhideDonation(1);
        $newUser->setHideUnhideBadges(0);
        $newUser->setPasswordToken('');
        $newUser->setRetrievePasswordTime(date('Y-m-d H:i:s'));
        $newUser->setEmail($email);
        $newUser->setCapabilities('registered');
        $newUser->changePassword($password);
		$newUser->setPromptPickUsername(0);

        $CI =& get_instance();
        $last_request_uri = $CI->session->userdata('prev_request_uri');
        $newUser->setUrlBeforeSignup($last_request_uri);

        try {
            \Base_Controller::$em->persist($newUser);
            \Base_Controller::$em->flush($newUser);

            $newUser->fixChallenges();
            $newUser->fixBets();

            return true;
        } catch (Exception $e) {
            return false;
        }

    }

    static public function fixWordForms($query) {
        $wordforms = file_get_contents(__DIR__.'/../../../conf/sphinx/wordforms.txt');
        $wordform_lines = explode("\n", $wordforms);
        foreach($wordform_lines as $wordform_line) {
            if (preg_match('#(.*)>(.*)#i', $wordform_line, $matches)) {
                $query = preg_replace('#\b'.trim($matches[1]).'\b#i', trim($matches[2]), $query);
            }
        }
        return $query;
    }
    static $pattern = null;

    static public function getQueryString($search_text) {

        if (!$search_text) {
            return $search_text;
        }

        if (function_exists('apc_fetch') && !self::$pattern) { // try to read from cache first
            self::$pattern = apc_fetch('stopwords_pattern', $success);
        }

        if (!self::$pattern) { // if reading from cache failed,, we need to read them from file.. and then add to cache!
            $stopwords = file_get_contents(__DIR__.'/../../../conf/sphinx/stopwords.txt');
            $tmp_stopwords = explode("\n", $stopwords);
            $stopwords = [];
            foreach($tmp_stopwords as $stopword) {
                $stopwords[] = trim($stopword);
            }
            self::$pattern = '#\b('.join('|',$stopwords).')\b#i';
            if (function_exists('apc_store')) {
                apc_store('stopwords_pattern', self::$pattern, 60*60); // 1 hour
            }
        }

        $stopwords_removed = preg_replace(self::$pattern, '', $search_text);

        $wordforms_fixed = self::fixWordForms($stopwords_removed);

        $tmp_words = explode(' ', $wordforms_fixed);

        $words = [];
        foreach($tmp_words as $word) {
            $trimmed_word = trim($word);
            if (!$trimmed_word) continue;

            $trimmed_word = preg_replace('#\.#', '', $trimmed_word);

            if (strtoupper($trimmed_word) == 'ALS') {
                $words[] = $trimmed_word . ' amyotrophic lateral sclerosis';
            } else {
                $words[] = strlen($trimmed_word)>2 ? '*'.$trimmed_word.'*' : $trimmed_word;
            }
        }

        if (count($words) == 1 && strlen($words[0]) <= 2) {
            $words[0] = '*'.$words[0].'*';
        }

        $ret = '"'.join(' ', $words).'"/' . max(floor(count($words)*0.6), 1);
        return $ret;
    }

    static public function slug($str, $convert_dots_to = '-dot-') {
        setlocale(LC_ALL, 'en_US.UTF8');

        $clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
        $clean = str_replace('@', '-at-', $clean);

        $clean = str_replace('.', $convert_dots_to, $clean);

        $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
        $clean = strtolower(trim($clean, '-'));
        $clean = preg_replace("/[\/_|+ -]+/", '-', $clean);
        $clean = strtolower(trim($clean, '-'));

        return $clean;
    }

    static public function truncate($text, $length = 100, $ending = '...', $exact = false, $considerHtml = true) {
        if ($considerHtml) {
            // if the plain text is shorter than the maximum length, return the whole text
            if (strlen(preg_replace('/<.*?>/', '', $text)) <= $length) {
                return $text;
            }
            // splits all html-tags to scanable lines
            preg_match_all('/(<.+?>)?([^<>]*)/s', $text, $lines, PREG_SET_ORDER);
            $total_length = strlen($ending);
            $open_tags = array();
            $truncate = '';
            foreach ($lines as $line_matchings) {
                // if there is any html-tag in this line, handle it and add it (uncounted) to the output
                if (!empty($line_matchings[1])) {
                    // if it's an "empty element" with or without xhtml-conform closing slash
                    if (preg_match('/^<(\s*.+?\/\s*|\s*(img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param)(\s.+?)?)>$/is', $line_matchings[1])) {
                        // do nothing
                        // if tag is a closing tag
                    } else if (preg_match('/^<\s*\/([^\s]+?)\s*>$/s', $line_matchings[1], $tag_matchings)) {
                        // delete tag from $open_tags list
                        $pos = array_search($tag_matchings[1], $open_tags);
                        if ($pos !== false) {
                            unset($open_tags[$pos]);
                        }
                        // if tag is an opening tag
                    } else if (preg_match('/^<\s*([^\s>!]+).*?>$/s', $line_matchings[1], $tag_matchings)) {
                        // add tag to the beginning of $open_tags list
                        array_unshift($open_tags, strtolower($tag_matchings[1]));
                    }
                    // add html-tag to $truncate'd text
                    $truncate .= $line_matchings[1];
                }
                // calculate the length of the plain text part of the line; handle entities as one character
                $content_length = strlen(preg_replace('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', ' ', $line_matchings[2]));
                if ($total_length+$content_length> $length) {
                    // the number of characters which are left
                    $left = $length - $total_length;
                    $entities_length = 0;
                    // search for html entities
                    if (preg_match_all('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', $line_matchings[2], $entities, PREG_OFFSET_CAPTURE)) {
                        // calculate the real length of all entities in the legal range
                        foreach ($entities[0] as $entity) {
                            if ($entity[1]+1-$entities_length <= $left) {
                                $left--;
                                $entities_length += strlen($entity[0]);
                            } else {
                                // no more characters left
                                break;
                            }
                        }
                    }
                    $truncate .= substr($line_matchings[2], 0, $left+$entities_length);
                    // maximum lenght is reached, so get off the loop
                    break;
                } else {
                    $truncate .= $line_matchings[2];
                    $total_length += $content_length;
                }
                // if the maximum length is reached, get off the loop
                if($total_length>= $length) {
                    break;
                }
            }
        } else {
            if (strlen($text) <= $length) {
                return $text;
            } else {
                $truncate = substr($text, 0, $length - strlen($ending));
            }
        }
        // if the words shouldn't be cut in the middle...
        if (!$exact) {
            // ...search the last occurance of a space...
            $spacepos = strrpos($truncate, ' ');
            if (isset($spacepos)) {
                // ...and cut the text in this position
                $truncate = substr($truncate, 0, $spacepos);
            }
        }
        // add the defined ending to the text
        $truncate .= $ending;
        if($considerHtml) {
            // close all unclosed html-tags
            foreach ($open_tags as $tag) {
                $truncate .= '</' . $tag . '>';
            }
        }
        return $truncate;
    }

    /**
     * @param $str
     *
     * @return string
     */
    static public function formatUrlsIntoLinks($str) {
        $words = explode(' ', $str);
        foreach($words as &$word) {
            $word = trim($word);
            if (substr($word, 0, 4) == 'http' && filter_var($word, FILTER_VALIDATE_URL)) {
                $href = htmlspecialchars($word);
                $word = '<a title="'.$href.'" href="'.$href.'" rel=nofollow>'.htmlspecialchars($word).'</a>';
            } elseif (preg_match('#^www\.|\.net$|\.com$|\.org$#', $word)){
                $href = 'http://'.htmlspecialchars($word);
                $word = '<a title="'.$href.'" href="'.$href.'" rel=nofollow>'.htmlspecialchars($word).'</a>';
            } else {
                $word = htmlspecialchars($word);
            }
        }

        return join(' ', $words);
    }

    static public function humanizeDateDifference(\DateTime $now, \DateTime $otherDate) {
        $now = $now->getTimestamp();
        $otherDate = $otherDate->getTimestamp();
        $offset = $now - $otherDate;
        $offset /= 60;
        $deltaM = $offset%60;
        $offset /= 60;
        $deltaH = $offset%24;
        $offset /= 24;
        $deltaD = ($offset > 1)?ceil($offset):$offset;

        if ($deltaD > 1) {
            if ($deltaD > 365) {
                $years = ceil($deltaD/365);
                if ($years == 1) {
                    return "last year";
                } else{
                    return "$years years ago";
                }
            }
            if($deltaD > 6){
                return date('d-M',strtotime("$deltaD days ago"));
            }
            return "$deltaD days ago";
        }
        if ($deltaD == 1) {
            return "Yesterday";
        }
        if ($deltaH == 1) {
            return "last hour";
        }
        if ($deltaM == 1) {
            return "last minute";
        }
        if ($deltaH > 0) {
            return $deltaH." hours ago";
        }
        if ($deltaM > 0) {
            return $deltaM." minutes ago";
        } else {
            return "a few seconds ago";
        }
    }

    static public function formatNumber($nr) {
        if ($nr < 100) {
            return $nr;
        }
        if ($nr < 1000000) {
            return round($nr / 1000, 1).'K';
        }
        return round($nr / 1000000, 1).'M';
    }

    static public function formatEin($ein) {
        return substr($ein,0,2) . '-' . substr($ein,2);
    }
}
