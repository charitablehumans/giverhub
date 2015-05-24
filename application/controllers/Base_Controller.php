<?php

require_once(__DIR__.'/../../system/core/Controller.php');
require_once(__DIR__.'/../models/Common.php');

if (defined('GOOGLE_API_VERSION') && GOOGLE_API_VERSION == 3) {
    require_once( __DIR__ . '/../libraries/google-api-php-client-3/src/Google/Client.php' );
    require_once( __DIR__ . '/../libraries/google-api-php-client-3/src/Google/Service/Oauth2.php' );
    $GLOBALS['GAPI'] = 3;
} else {
    require_once( __DIR__ . '/../libraries/google-api-php-client-2/src/Google/Client.php' );
    require_once( __DIR__ . '/../libraries/google-api-php-client-2/src/Google/Service/Oauth2.php' );
    $GLOBALS['GAPI'] = 2;
}

use Doctrine\ORM\EntityManager;
use Entity\User;

class GiverHub404Exception extends \Exception {}

/**
 * Class Base_Controller
 *
 * @property object $session
 * @property object $carabiner
 */
class Base_Controller extends \CI_Controller {

    /** @var EntityManager */
    static public $em;

    /** @var User */
    public $user;

    /** @var User */
    static public $staticUser;


    public $googleAuthUrl;

    public $headerPrefix;

    public $metaDesc = 'Donate to your favorite charity now. GiverHub makes being charitable fast, fun and... funvenient.';
    public $metaKeys = 'donate,charity,donation,charities';
    public $htmlTitle;
    public $ogTitle = 'GiverHub, Inc.';
    //public $ogDesc = 'GiverHub makes it crazy easy to donate to any charity AND it keeps track of all your donations in one convenient location. Leave reviews, view financial information, see what charities your friends are donating to and, as always, give back the fun and convenient way, with GiverHub.';
    public $ogDesc = 'GiverHub: Donate instantly, itemize automatically.';
    public $ogImage = [];
    public $ogType = 'website';
    public $donation_modals_included_already = false;
    public $bet_modals_included_already = false;

    public $extra_headers = [];

    public $my_dashboard = false;

    public $body_class = '';

    public $route;

    /** @var \Entity\User */
    public $dashboard_dropdown = false;

    public function __construct() {
        parent::__construct();


        $GLOBALS['super_timers']['tttt1'] = microtime(true) - $GLOBALS['super_start'];

        $this->ogImage[] = (@$_SERVER['SERVER_PORT'] == 443 ? 'https' : 'http') . '://' . @$_SERVER['SERVER_NAME'] . '/img/fblogo-square.png';

        $this->load->library('form_validation');

        $this->load->config('general_website_conf');

        $this->load->library('carabiner');

        $this->route = $this->router->fetch_class().'/'.$this->router->fetch_method();

        $this->carabiner->js('wysihtml5/wysihtml5-0.4.0.js');

        $this->carabiner->js('jquery.js');
        $this->carabiner->js('jquery-ui.js');
        $this->carabiner->js('bootstrap.js');
        $this->carabiner->js('popover.js');
        $this->carabiner->js('tooltip.js');
        $this->carabiner->js('jquery-file-upload/jquery.fileupload.js');
        $this->carabiner->js('jquery.autoellipsis-1.0.10.min.js');
        $this->carabiner->js('jquery.elastic.js');
        $this->carabiner->js('moment.min.js');
        $this->carabiner->js('bootstrap-datetimepicker.js');
        $this->carabiner->js('fullcalendar.min.js');
        $this->carabiner->js('perfect-scrollbar.min.js');
        $this->carabiner->js('perfect-scrollbar.with-mousewheel.min.js');


        $this->carabiner->js('jquery.dotdotdot.js');

        $this->carabiner->js('handlebars.runtime.min.js');
        $this->carabiner->js('handlebars-templates/handlebars_helpers.js');
        $this->carabiner->js('handlebars-templates/autocomplete_results.js');
        $this->carabiner->js('handlebars-templates/bet_determination_modal.js');
        $this->carabiner->js('handlebars-templates/view_bet_modal.js');
        $this->carabiner->js('handlebars-templates/giverhub_petition_create_modal.js');
        $this->carabiner->js('handlebars-templates/giverhub_petition_preview_modal.js');
        $this->carabiner->js('handlebars-templates/giverhub_petition_publish_success_modal.js');
        $this->carabiner->js('handlebars-templates/challenges/challenge_preview_block.js');
        $this->carabiner->js('handlebars-templates/giving-pot/giving_pot.js');
        $this->carabiner->js('handlebars-templates/giving-pot/giving_pot_recipient_form_row.js');


        $this->carabiner->js('wysihtml5/bootstrap3-wysihtml5.js');
        $this->carabiner->js('wysihtml5/jquery.wysihtml5_size_matters.js');

        $this->carabiner->js('card/card.js');

        $this->carabiner->js('modules/center-all-modals.js');
        $this->carabiner->js('video-js/video.dev.js');
        $this->carabiner->js('modules/stat.js');
        $this->carabiner->js('giverhub.js');

        $this->carabiner->js('modules/trigger-ellipsis.js');
        $this->carabiner->js('modules/analytics-events.js');

        if (CLOSED_BETA) {
            $this->carabiner->js('modules/closed-beta.js');
        }

        $this->carabiner->js('modules/share-buttons.js');
        $this->carabiner->js('modules/navbar.js');
        $this->carabiner->js('modules/charity-profile.js');
        $this->carabiner->js('modules/keyword-actions.js');
        $this->carabiner->js('modules/charity-manage-keywords.js');
        $this->carabiner->js('modules/member-profile.js');
        $this->carabiner->js('modules/donations.js');
        $this->carabiner->js('modules/address.js');
        $this->carabiner->js('modules/find-friends.js');
        $this->carabiner->js('modules/faq.js');
        $this->carabiner->js('modules/member-settings.js');
        $this->carabiner->js('modules/member-followers.js');
        $this->carabiner->js('modules/forgot-password.js');
        $this->carabiner->js('modules/petition.js');
        $this->carabiner->js('modules/bet.js');
        $this->carabiner->js('modules/member-search.js');
        $this->carabiner->js('modules/search.js');
        $this->carabiner->js('modules/missions.js');
        $this->carabiner->js('modules/activity-feed.js');
        $this->carabiner->js('modules/activity-feed-infinity-scroll.js');
        $this->carabiner->js('modules/blog.js');
        $this->carabiner->js('modules/charity-admin.js');
        $this->carabiner->js('modules/charity-admin-data.js');
        $this->carabiner->js('modules/giverhub-petitions.js');
        $this->carabiner->js('modules/giver-cards.js');
        $this->carabiner->js('modules/challenges.js');
        $this->carabiner->js('modules/dashboard-buttons.js');
        $this->carabiner->js('modules/citizen-data.js');
        $this->carabiner->js('modules/learn-more.js');
        $this->carabiner->js('modules/page.js');
        $this->carabiner->js('modules/giving-pot.js');
        $this->carabiner->js('modules/donation-history.js');
        $this->carabiner->js('modules/mobile-menu.js');
        $this->carabiner->js('modules/volcal.js');



        $this->carabiner->js('angular/angular.js');
        $this->carabiner->js('angular/ui-bootstrap-tpls-0.11.0.js');
        $this->carabiner->js('angular/angular-moment.min.js');
        $this->carabiner->js('angular/angular-wysiwyg.js');
        $this->carabiner->js('angular/elastic.js');
        $this->carabiner->js('angular/angular-perfect-scrollbar.js');

        $this->carabiner->js('angular/shared/shared.js');
        $this->carabiner->js('angular/volunteering-opportunities/volunteering-opportunities.js');
        $this->carabiner->js('angular/messages/messages.js');


        $this->carabiner->js('joyride-tooltip/jquery.cookie.js');
        $this->carabiner->js('joyride-tooltip/modernizr.mq.js');
        $this->carabiner->js('joyride-tooltip/jquery.joyride-2.1.js');
        $this->carabiner->css('joyride-2.1.css');

        $this->carabiner->js('wysiwyg/summernote.js');
        $this->carabiner->css('wysiwyg/summernote.css');
        $this->carabiner->css('wysiwyg/summernote-bs3.css');

        $this->carabiner->css('utf8.css');
        $this->carabiner->css('giverhub.css');
        $this->carabiner->css('font-awesome.css');
        $this->carabiner->css('font-awesome2.css');
        $this->carabiner->css('jquery-ui-autocomplete.css');
        $this->carabiner->css('jquery-file-upload/jquery.fileupload-ui.css');
        $this->carabiner->css('bootstrap-datetimepicker.min.css');
        $this->carabiner->css('bootstrap3-wysihtml5.min.css');
        $this->carabiner->css('card/card.css');
        $this->carabiner->css('video-js/video-js.css');
        $this->carabiner->css('fullcalendar.min.css');
        $this->carabiner->css('perfect-scrollbar.min.css');

        $this->load->library('doctrine');

        self::$em = $this->doctrine->em;
        self::$em->getConnection()->getConfiguration()->setSQLLogger(null);
        static::$em = self::$em;
        if ($this->session->userdata('id')) {
            self::$staticUser = $this->user = static::$em->getRepository('Entity\User')->find(
                                                         $this->session->userdata('id')
            );
        } else {
            $GLOBALS['super_timers']['tttt2'] = microtime(true) - $GLOBALS['super_start'];
            $this->googleAuth();
            $GLOBALS['super_timers']['tttt3'] = microtime(true) - $GLOBALS['super_start'];
        }

        if (!$this->session->userdata('CSRFToken')) {
            $this->session->set_userdata('CSRFToken', sha1(rand()));
        }


        if ($this->user) {
            // check if this is the first_request during this session.. should get reset automatically every time user signs out / in
            $first_request = $this->session->userdata('first_request');
            if ($first_request === false) {
                $this->session->set_userdata('first_request', 1);
            } elseif ($first_request === 1) {
                $this->session->set_userdata('first_request', 0); // codeigniter does not differentiate between an unset session variable and a session variable set to false.. both simply return false.. go to hell codeigniter
            }
        }

        if (!$this->user) {

            $request_uri = @$_SERVER['REQUEST_URI'];
            if (!in_array($request_uri, [
                '/home/fbLogin',
                '/stat/register',
            ]) && (@$_SERVER['HTTP_X_REQUESTED_WITH'] !== 'XMLHttpRequest')) {
                $this->session->set_userdata( 'prev_request_uri', $this->session->userdata( 'request_uri' ) );
                $this->session->set_userdata( 'request_uri', @$_SERVER['REQUEST_URI'] );
            }
        }
        $GLOBALS['super_timers']['tttt4'] = microtime(true) - $GLOBALS['super_start'];
    }

    public function getUserCount() {
        if (!$this->user || !$this->user->isAdmin()) {
            throw new Exception('Only admins.');
        }
        $data = apc_fetch('user_count', $success);
        if ($success) {
            return $data;
        }
        $q = $this->db->query('select count(id) as cnt from users');
        $rows = $q->result_array();
        apc_store('user_count', $rows[0]['cnt'], 60*60*24);
        return $rows[0]['cnt'];
    }

    public function googleAuth() {
        $client = new \Google_Client();
        $client->setApplicationName("GiverHub");
        $client->setScopes('email');
        $oauth2 = new \Google_Service_Oauth2($client);

        $client->setClientId($this->config->item('google_client_id'));
        $client->setClientSecret($this->config->item('google_client_secret'));
        $client->setRedirectUri($this->config->item('google_redirect_uri'));
        $client->setDeveloperKey($this->config->item('google_developer_key'));

        if (isset($_REQUEST['logout'])) {
            $this->session->unset_userdata('google_access_token');
        }

        if (isset($_GET['code'])) {
            try {
                $client->authenticate($_GET['code']);
            } catch (Exception $e) {
                $this->session->set_flashdata('flashError', 'Something went wrong when authenticating your google access token. Please try again. Thank you for your patience.');
                redirect('/');
            }
            $this->session->set_userdata('google_access_token', $client->getAccessToken());

            $user = Common::create_member_google(
                                           $oauth2->userinfo->get(),
                                               static::$em->getRepository('Entity\User'),
                                               static::$em
            );

            if (!$user instanceof User) {
                $this->session->set_flashdata('flashError', $user);
                redirect('/');
                return;
            } else {
                $user->login($this->session);
            }

            if ($this->input->get('state')) {
                $redirect = $this->input->get('state');
            } else {
                $redirect = '/members';
            }
            $this->session->set_flashdata('event_signed_in', 'google');
            redirect(($_SERVER['SERVER_PORT'] == 443 ? 'https' : 'http') .'://' . $_SERVER['SERVER_NAME'] . $redirect);
        }

        if ($this->session->userdata('google_access_token')) {
            $client->setAccessToken($this->session->userdata('google_access_token'));
        }

        if ($client->getAccessToken()) {
            $this->session->set_userdata('google_access_token', $client->getAccessToken());
        } else {
            $this->googleAuthUrl = preg_replace('#\&#', '&amp;', $client->createAuthUrl());
        }
    }

    public $googleUrl;

    public function getGoogleUrl($redirect = "") {
        if ($this->googleUrl) {
            return $this->googleUrl;
        }

        $client = new \Google_Client();
        $client->setApplicationName("GiverHub");
        $client->setScopes('email');
        $oauth2 = new \Google_Service_Oauth2($client);

        $client->setClientId($this->config->item('google_client_id'));
        $client->setClientSecret($this->config->item('google_client_secret'));
        $client->setRedirectUri($this->config->item('google_redirect_uri'));
        $client->setDeveloperKey($this->config->item('google_developer_key'));


        if ($this->session->userdata('google_access_token')) {
            $client->setAccessToken($this->session->userdata('google_access_token'));
        }

        if ($client->getAccessToken()) {
            $this->session->set_userdata('google_access_token', $client->getAccessToken());
        }

        $client->setState($redirect);
        $this->googleUrl = preg_replace('#\&#', '&amp;', $client->createAuthUrl());

        return $this->googleUrl;
    }


    public function getStates() {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = self::$em;

        /** @var \Doctrine\ORM\EntityRepository $stateRepository */
        $stateRepository = $em->getRepository('Entity\CharityState');

        /** @var \Entity\CharityState[] $states */
        $states = $stateRepository->findBy(array(), array('fullName' => 'asc'));

        return $states;
    }

    public function getCurrentUrl() {
        $isHTTPS = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on");
        $port = (isset($_SERVER["SERVER_PORT"]) && ((!$isHTTPS && $_SERVER["SERVER_PORT"] != "80") || ($isHTTPS && $_SERVER["SERVER_PORT"] != "443")));
        $port = ($port) ? ':'.$_SERVER["SERVER_PORT"] : '';
        $url = ($isHTTPS ? 'https://' : 'http://').$_SERVER["SERVER_NAME"].$port.$_SERVER["REQUEST_URI"];
        return $url;
    }

    protected function giverhub_404($template, $htmlTitle) {
        $this->output->set_status_header('404');
        $this->htmlTitle = $htmlTitle;
        $data['main_content'] = $template;

        $ignore_list = [
            '/browserconfig.xml', // ie6 insanity :)
            '/html5', // baiduspider
            '/wp-login.php', // fucking hackers
        ];

        if (!preg_match('#^/sharer\.php#', $_SERVER['REQUEST_URI']) &&
            !preg_match('#^/plugins/registration#', $_SERVER['REQUEST_URI']) &&
            !preg_match('#CachedWe$#', $_SERVER['REQUEST_URI']) &&
            !in_array($_SERVER['REQUEST_URI'], $ignore_list)) { // send email only if request-uri is not in the ignore list
            $server = print_r($_SERVER, true);
            $post = print_r($_POST, true);
            $get = print_r($_GET, true);
            $request = print_r($_REQUEST, true);
            $msg =
"_SERVER[REQUEST_URI]: {$_SERVER['REQUEST_URI']}
_POST: {$post}
_GET: {$get}
_REQUEST: {$request}
_SERVER: {$server}";

            mail('admin@giverhub.com', '404 @' . @$_SERVER['SERVER_NAME'] . ' ' . @$_SERVER['REQUEST_URI'], $msg);
        }

        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $json = ['success' => false];
            echo json_encode($json);
            return;
        }

        $this->load->view('includes/user/template', $data);
    }

    public function getCarabinerAsset($type) {
        if (!in_array($type, ['js', 'css'])) {
            throw new Exception('Invalid type for carabiner asset. needs to be js or css. but ' . $type . ' was provided');
        }

        try {
            $cache_dir = __DIR__.'/../../assets/cache/';
            $live_dir = __DIR__.'/../../assets/live/';

            $x = 0;
            $latest = [];
            while(true) {
                $filename = 'giverhub_'.$x.'.'.$type;
                $path_plus_filename = $live_dir.$filename;
                if (is_file($path_plus_filename)) {
                    $latest['nr'] = $x;
                    $latest['filename'] = $filename;
                    $latest['path_plus_filename'] = $path_plus_filename;
                } else {
                    break;
                }
                $x++;
            }

            if (GIVERHUB_LIVE && !empty($latest)) {
                $name  = $latest['filename'];
            } else {
                $str = $this->carabiner->display_string($type);
                preg_match('#assets/cache/(.*)\.'.$type.'#', $str, $matches);
                $cached_file = $cache_dir.$matches[1].'.'.$type;
                if (empty($latest)) {
                    $name = 'giverhub_0.'.$type;
                    $to = $live_dir.$name;
                    if (!@copy($cached_file, $to)) {
                        throw new Exception('Failed copying carabiner resource of type ' . $type . ' from ' . $cached_file . ' to ' . $to);
                    }
                } else {
                    $cached_contents = file_get_contents($cached_file);
                    $cached_contents_crc32 = crc32($cached_contents);
                    $latest_contents = file_get_contents($latest['path_plus_filename']);
                    $latest_contents_crc32 = crc32($latest_contents);

                    if ($cached_contents_crc32 == $latest_contents_crc32) {
                        $name = $latest['filename'];
                    } else {
                        $name = 'giverhub_'. ($latest['nr']+1) . '.' . $type;
                        $to = $live_dir.$name;
                        if (!@copy($cached_file, $to)) {
                            throw new Exception('Failed copying carabiner resource of type ' . $type . ' from ' . $cached_file . ' to ' . $to);
                        }
                    }
                }
            }

            if ($type == 'js') {
                echo '<script type="text/javascript" src="/assets/live/'.$name.'" charset="UTF-8"></script>';
            } elseif ($type == 'css') {
                echo '<link type="text/css" rel="stylesheet" href="/assets/live/'.$name.'" media="screen">';
            }

        } catch(Exception $e) {
            $this->carabiner->display($type);
        }
    }

    public function modal($id, array $options = []) {
        $this->load->view('_modal', [
            'id' => $id,
            'extra_classes' => isset($options['extra_classes']) ? $options['extra_classes'] : '',
            'modal_size' => isset($options['modal_size']) ? $options['modal_size'] : 'col-md-4',
            'header' => isset($options['header']) ? $options['header'] : '&nbsp;',
            'body' => isset($options['body']) ? $options['body'] : '',
            'body_string' => isset($options['body_string']) ? $options['body_string'] : true,
            'body_data' => isset($options['body_data']) ? $options['body_data'] : [],
            'footer' => isset($options['footer']) ? $options['footer'] : '',
            'footer_string' => isset($options['footer_string']) ? $options['footer_string'] : true,
            'body_wrapper' => isset($options['body_wrapper']) ? $options['body_wrapper'] : true,
            'extra_attributes' => isset($options['extra_attributes']) ? $options['extra_attributes'] : '',
        ]);
    }
}