<?php
$GLOBALS['super_timers']['eeee1'] = microtime(true) - $GLOBALS['super_start'];
/** @var string $main_content */
/** @var \Base_Controller $CI */
$CI =& get_instance();

if (!isset($current_search)) {
    $current_search_after = '';
} else {
    $current_search_after = $current_search;
}

$GLOBALS['route'] = $this->route;

function active($routes, $echo = true, $ignore_first_only = false) {
    static $_active = false;
    if (!$ignore_first_only && $_active) { // return active only once
        if ($echo) {
            echo '';
        }
        return false;
    }
    $route = $GLOBALS['route'];
    if (!is_array($routes)) {
        $routes = [$routes];
    }

    $active = in_array($route, $routes);
    if ($echo) {
        echo $active ? 'active' : '';
    }
    if ($active) {
        $_active = true; // only once!
    }
    return $active;
}

?><!DOCTYPE html>
<!--[if lt IE 7 ]>
<html xmlns="http://www.w3.org/1999/xhtml" prefix="fb: http://ogp.me/ns/fb#" class="no-js ie6" lang="en"><![endif]-->
<!--[if IE 7 ]>
<html xmlns="http://www.w3.org/1999/xhtml" prefix="fb: http://ogp.me/ns/fb#" class="no-js ie7" lang="en"><![endif]-->
<!--[if IE 8 ]>
<html xmlns="http://www.w3.org/1999/xhtml" prefix="fb: http://ogp.me/ns/fb#" class="no-js ie8" lang="en"><![endif]-->
<!--[if IE 9 ]>
<html xmlns="http://www.w3.org/1999/xhtml" prefix="fb: http://ogp.me/ns/fb#" class="no-js ie9" lang="en"><![endif]-->
<html lang="en" prefix="fb: http://ogp.me/ns/fb#">
<head <?php if ($CI->headerPrefix): ?>prefix="<?php echo $CI->headerPrefix; ?>"<?php endif; ?>>
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="keywords" content="<?php echo htmlspecialchars($CI->metaKeys); ?>">
    <meta name="description" content="<?php echo htmlspecialchars($CI->metaDesc); ?>">

    <meta name="author" content="">

    <meta name="google-site-verification" content="5xBAuG8BbJhl3hWtSl_c_o7JYTcS-nDSHI6ECGBwEkE" />

    <title><?php echo $CI->htmlTitle ? htmlspecialchars($CI->htmlTitle) . ' - ' : ''; ?>GiverHub.com</title>

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="/assets/scripts/html5shiv.js"></script>
    <script src="/assets/scripts/respond.min.js"></script>
    <![endif]-->

    <!-- Favicons -->
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="/assets/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="/assets/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="/assets/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="/assets/ico/apple-touch-icon-57-precomposed.png">
    <link rel="shortcut icon" href="/assets/ico/favicon.png?v=2">
    <link rel="icon" href="/assets/ico/favicon.ico?v=2" sizes="16x16 24x24 32x32 128x128" type="image/vnd.microsoft.icon">

    <!--[if IE 7]>
    <link rel="stylesheet" href="/assets/styles/font-awesome-ie7.css">
    <![endif]-->

    <meta name="google" content="notranslate">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <meta property="og:description"
          content="<?php echo htmlspecialchars($CI->ogDesc); ?>">

    <?php foreach($CI->ogImage as $ogImage): ?>
        <meta property="og:image" content="<?php echo htmlspecialchars($ogImage); ?>">
    <?php endforeach; ?>


    <meta property="og:title" content="<?php echo htmlspecialchars($CI->ogTitle); ?>">

    <meta property="fb:app_id" content="<?php echo htmlspecialchars($this->config->item("fb_app_id")); ?>">
    <meta property="og:url" content="<?php echo ($_SERVER['SERVER_PORT'] == 443 ? 'https' : 'http') . '://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']; ?>">

    <meta property="og:type" content="<?php echo htmlspecialchars($CI->ogType); ?>" />

    <meta property="og:site_name" content="GiverHub">

    <?php foreach($CI->extra_headers as $extra_header): ?>
        <?php echo $extra_header; ?>
    <?php endforeach; ?>

    <!-- Stylesheets -->
    <?php $CI->getCarabinerAsset('css'); ?>
</head>
<?php $GLOBALS['super_timers']['eeee4'] = microtime(true) - $GLOBALS['super_start']; ?>
<body data-signed-in="<?php echo $CI->user ? $CI->user->getId() : 0; ?>"
      data-user-is-admin="<?php echo $CI->user && $CI->user->isAdmin() ? 1 : 0; ?>"
      <?php if ($CI->user): ?>
        data-signed-in-user="<?php echo htmlspecialchars(json_encode($CI->user)); ?>"
      <?php endif; ?>

      class="<?php echo $CI->body_class; ?> <?php echo $CI->router->class . '-' . $CI->router->method; ?> <?php echo $CI->router->class; ?> <?php echo $CI->router->method; ?>"

      data-closed-beta="<?php echo CLOSED_BETA ? 1 : 0; ?>"
      data-missing-name="<?php echo $CI->user && $CI->user->isMissingName() ? 1 : 0; ?>"
      data-choose-username="<?php echo $CI->user && $CI->user->getPromptPickUsername() ? 1 : 0; ?>"
      data-fb-app-id="<?php echo $this->config->item('fb_app_id'); ?>"
      data-current-url="<?php echo $CI->getCurrentUrl(); ?>"
      data-instant-donations="<?php echo $CI->user && $CI->user->isInstantDonationsEnabled() ? 1 : 0; ?>"
      data-giverhub-debug="<?php echo GIVERHUB_DEBUG ? 1 : 0; ?>"
      data-giverhub-live="<?php echo GIVERHUB_LIVE ? 1 : 0; ?>"
      data-csrf-token="<?php echo htmlspecialchars($this->session->userdata('CSRFToken')); ?>"
      data-base-url="<?php echo base_url(); ?>"
      <?php if (isset($charity) && $charity instanceof \Entity\Charity): ?>data-charity-id="<?php echo $charity->getId(); ?>"<?php endif; ?>
      data-flash-error="<?php if ($this->session->flashdata('flashError')): ?><?php echo htmlspecialchars($this->session->flashdata('flashError')); ?><?php endif; ?>"
      <?php if ($CI->user && $CI->user->hasAddress()): ?>data-default-address-id="<?php echo $CI->user->getDefaultAddressId(); ?>"<?php endif; ?>
>
<?php $GLOBALS['super_timers']['eeee4_1'] = microtime(true) - $GLOBALS['super_start']; ?>

    <div id="fb-root"></div>
    <?php if (GIVERHUB_LIVE): ?>
        <script type="text/javascript">
            (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

            ga('create', 'UA-46123922-1', 'auto');
            ga('send', 'pageview');

            <?php if ($CI->user && $CI->user->isAdmin()): ?>
                ga('set', 'dimension1', 'HideGiverhubAdmin');
            <?php endif; ?>

            <?php $signed_up_event = $CI->session->flashdata('event_signed_up'); ?>
            <?php if ($signed_up_event): ?>
                ga('send', 'event', 'signup', '<?php echo $signed_up_event; ?>');
                ga('send', 'pageview', '/virtual/signup/<?php echo $signed_up_event; ?>');
                <?php if (!GIVERHUB_LIVE): ?>
                    console.log('signed up');
                <?php endif; ?>
            <?php endif; ?>

            <?php $GLOBALS['super_timers']['eeee4_2'] = microtime(true) - $GLOBALS['super_start']; ?>

            <?php $signed_in_event = $CI->session->flashdata('event_signed_in'); ?>
            <?php if ($signed_in_event): ?>
                ga('send', 'event', 'signin', '<?php echo $signed_in_event; ?>');
                ga('send', 'pageview', '/virtual/signin/<?php echo $signed_in_event; ?>');
                <?php if (!GIVERHUB_LIVE): ?>
                    console.log('signed in');
                <?php endif; ?>
            <?php endif; ?>

            <?php if (isset($ga_events) && is_array($ga_events)): ?>
                <?php foreach($ga_events as $ga_event): ?>
                    ga(
                        'send',
                        'event',
                        '<?php echo $ga_event['category']; ?>'
                        <?php if (isset($ga_event['action'])): ?>,'<?php echo $ga_event['action']; ?>'<?php endif; ?>
                        <?php if (isset($ga_event['label'])): ?>,'<?php echo $ga_event['label']; ?>'<?php endif; ?>
                        <?php if (isset($ga_event['value'])): ?>,'<?php echo $ga_event['value']; ?>'<?php endif; ?>
                    );
                <?php endforeach; ?>
            <?php endif; ?>
        </script>
    <?php endif; ?>
    <a id="current-url" class="hide" href="#"></a>
    <?php $GLOBALS['super_timers']['eeee4_3'] = microtime(true) - $GLOBALS['super_start']; ?>
    <?php $CI->getCarabinerAsset('js'); ?>
    <?php $GLOBALS['super_timers']['eeee4_4'] = microtime(true) - $GLOBALS['super_start']; ?>

    <script type="text/javascript">
        var appId = '<?php echo $this->config->item('fb_app_id'); ?>';
        var site_url_js = '<?php echo base_url();?>';
        var channelUrl = '//<?php echo $_SERVER['SERVER_NAME']; ?>/channel.html';


        var fbInitHasBeenRun = false;
        var runLoginAfterFbInit = false;

        <?php if (isset($triggerFbLogin)): ?>
            fbjslogin('<?php echo $triggerFbLogin; ?>');
        <?php endif; ?>
    </script>
    <a class="sr-only" href="#content">Skip navigation</a>
    <?php $GLOBALS['super_timers']['eeee4_5'] = microtime(true) - $GLOBALS['super_start']; ?>
    <?php if (!$CI->user && $main_content == 'home/dual_landing_page'): ?>
        <?php $GLOBALS['super_timers']['eeee4_5_1'] = microtime(true) - $GLOBALS['super_start']; ?>
        <?php $this->load->view('/partials/navbar/dual-landing-page'); ?>
        <?php $GLOBALS['super_timers']['eeee4_5_2'] = microtime(true) - $GLOBALS['super_start']; ?>
    <?php else: ?>
        <?php $GLOBALS['super_timers']['eeee4_5_3'] = microtime(true) - $GLOBALS['super_start']; ?>
        <?php $this->load->view('/partials/navbar/regular'); ?>
        <?php $GLOBALS['super_timers']['eeee4_5_4'] = microtime(true) - $GLOBALS['super_start']; ?>
    <?php endif; ?>
    <?php $GLOBALS['super_timers']['eeee4_6'] = microtime(true) - $GLOBALS['super_start']; ?>
    <div class="mobile-menu-bg">
        <div class="mobile-menu">
            <ul class="fb-style-menu">
            </ul>
        </div>
    </div>
    <?php $GLOBALS['super_timers']['eeee4_7'] = microtime(true) - $GLOBALS['super_start']; ?>
    <div class="main-content">
        <div id="show_loading_message" class="container"></div>
        <?php $GLOBALS['super_timers']['eeee5'.$main_content] = microtime(true) - $GLOBALS['super_start']; ?>
        <?php $this->load->view($main_content); ?>
        <?php $GLOBALS['super_timers']['eeee6'.$main_content] = microtime(true) - $GLOBALS['super_start']; ?>
    </div>

    <footer class="gh_footer" role="contentinfo">

        <section class="container">
            <div class="col-md-7 gh_footer_nav_pills_wrapper">

                <ul class="nav nav-pills">
                    <li>
                        <a href="/">Home</a>
                    </li>

                    <li>
                        <a href="/home/contact">Contact Us</a>
                    </li>


                    <li>
                        <a href="/home/privacy">Privacy Policy</a>
                    </li>

                    <li>
                        <a href="/a-z">Sitemap</a>
                    </li>
                    <?php if (!$CI->user): ?>
                        <li>
                            <a
                                class="gh-trigger-event"
                                data-event-category="button"
                                data-event-action="click"
                                data-event-label="sign in (footer)"
                                data-toggle="modal"
                                data-target="#sign-in-modal-2"
                                href="#">Sign In</a>
                        </li>

                        <li>
                            <a  class="gh-trigger-event"
                                data-event-category="button"
                                data-event-action="click"
                                data-event-label="join (footer)"
                                data-toggle="modal"
                                data-target="#sign-up-modal-2"
                                href="#">Join</a>
                        </li>
                    <?php endif; ?>
                </ul>
                <!-- nav-pills end -->


                <div class="copy">&copy;<?php echo date('Y'); ?> GiverHub</div>
            </div>
            <!-- col-md- end -->

            <div class="col-md-5">
                <div class="col-md-12 col-lg-12 gh_footer_logo">
                    <img src="/img/nfg.png" alt="NetworkForGood">
                    <img src="/img/logo.png" alt="GiverHub">
                </div>

            </div>

        </section>
        <!-- container end -->

    </footer>

    <?php $CI->modal('sign-in-modal-2', [
        'modal_size' => 'modal-size-setter-ugh col-xs-12 col-md-8 col-lg-6',
        'header' => 'Sign in',
        'body' => '/modals/_sign-in-modal-2-body',
        'body_string' => false,
        'footer' => '<a data-target="#forgot-password-modal" data-dismiss="modal" data-toggle="modal" href="#" class="pull-left">Forgot Password?</a>'
    ]); ?>



    <?php if (!$CI->user): ?>
        <?php $CI->modal('signup-modal-google', [
            'modal_size' => 'col-md-6',
            'header' => 'Sign Up Using Google',
            'body' => '/modals/signup-modal-google-body',
            'body_string' => false,
        ]); ?>

        <?php $CI->modal('signin-or-join-first-modal', [
            'modal_size' => 'col-md-5 col-lg-3',
            'header' => 'Sign In or Join First',
            'body' => '/modals/signin-or-join-first-modal-body',
            'body_string' => false,
            'footer' => '/modals/signin-or-join-first-modal-footer',
            'footer_string' => false,
        ]); ?>

        <?php $CI->modal('signin-to-sign-petitions-modal', [
            'modal_size' => 'modal-size-setter-ugh col-xs-12 col-md-6 col-lg-5',
            'header' => 'Sign In to Sign Petitions',
            'body' => '/modals/signin-to-sign-petitions-modal-body',
            'body_string' => false,
            'footer' => '/modals/signin-to-sign-petitions-modal-footer',
            'footer_string' => false,
        ]); ?>

        <?php $CI->modal('sign-in-or-join-first-modal-2', [
            'modal_size' => 'modal-size-setter-ugh col-xs-12 col-md-8 col-lg-6',
            'header' => 'Sign In or Join First',
            'body' => '/modals/sign-in-or-join-first-modal-2-body',
            'body_string' => false,
        ]); ?>

        <?php $CI->modal('sign-up-modal-2', [
            'modal_size' => 'modal-size-setter-ugh col-xs-12 col-md-8 col-lg-6',
            'header' => 'Sign Up',
            'body' => '/modals/sign-up-modal-2-body',
            'body_string' => false,
        ]); ?>

    <?php endif; ?>

    <?php $CI->modal('forgot-password-modal', [
        'modal_size' => 'col-lg-4 col-md-5',
        'header' => 'Reset Password',
        'body' => '/modals/forgot-password-modal-body',
        'body_string' => false,
        'body_wrapper' => false,
        'footer' => '/modals/forgot-password-modal-footer',
        'footer_string' => false,
    ]); ?>

    <?php if (!$CI->user): ?>
        <?php $CI->modal('signup-modal-facebook', [
            'modal_size' => 'col-md-6',
            'header' => 'Sign Up With Facebook',
            'body' => '/modals/signup-modal-facebook-body',
            'body_string' => false,
        ]); ?>
    <?php endif; ?>

    <?php $CI->modal('giverhub-error-modal', [
        'extra_classes' => 'giverhub-alert',
        'modal_size' => 'col-md-5 col-lg-4',
        'header' => '<span id="giverhub-error-modal-subject" data-default-subject="Error!"></span>',
        'body' => '/modals/giverhub-error-modal-body',
        'body_string' => false,
        'footer' => '/modals/giverhub-error-modal-footer',
        'footer_string' => false,
    ]); ?>

    <?php $CI->modal('giverhub-success-modal', [
        'extra_classes' => 'giverhub-alert',
        'modal_size' => 'col-md-5 col-lg-4',
        'header' => '<span id="giverhub-success-modal-subject" data-default-subject="Success!" class="txtCntr"></span>',
        'body' => '/modals/giverhub-success-modal-body',
        'body_string' => false,
        'footer' => '/modals/giverhub-success-modal-footer',
        'footer_string' => false,
    ]); ?>

    <?php $CI->modal('giverhub-prompt-modal', [
        'extra_classes' => 'giverhub-alert',
        'modal_size' => 'col-md-4 col-lg-3',
        'body' => '/modals/giverhub-prompt-modal-body',
        'body_string' => false,
        'footer' => '/modals/giverhub-prompt-modal-footer',
        'footer_string' => false,
    ]); ?>


    <?php if (GIVERHUB_DEBUG): ?>
        <?php $CI->modal('giverhub-debug-modal', [
            'body' => $GLOBALS['debugmsg'] ? $GLOBALS['debugmsg'] : 'no debug info...',
            'body_string' => true,
            'footer' => '<a href="#" class="btn btn-primary" data-dismiss="modal" aria-hidden="true">Close</a>',
            'footer_string' => true,
        ]); ?>
    <?php endif; ?>

    <?php $CI->modal('feedback-modal', [
        'header' => 'Feedback',
        'modal_size' => 'col-md-5 col-lg-4',
        'body' => '/modals/feedback-modal-body',
        'body_string' => false,
        'footer' => '/modals/feedback-modal-footer',
        'footer_string' => false,
    ]); ?>


    <?php if($CI->user && $CI->user->isMissingName()): ?>
        <?php $CI->modal('missing-name-modal', [
            'header' => 'Enter Name!',
            'modal_size' => 'col-md-5 col-lg-4',
            'body' => '/modals/missing-name-modal-body',
            'body_string' => false,
            'footer' => '<a href="#" class="btn btn-primary btn-save-missing-name" data-loading-text="SAVING...">SAVE</a>',
            'footer_string' => true,
        ]); ?>
    <?php endif; ?>

    <?php if (false && $CI->user && $CI->session->userdata('first_request')): ?>
        <?php $bets_in_need_of_determination = $CI->user->getBetsInNeedOfDeterminationJSON(); ?>
        <script type="text/javascript">
            window.bets_in_need_of_determination = <?php echo $bets_in_need_of_determination; ?>;
        </script>

        <?php $CI->modal('bet-determination-modal', [
            'header' => 'Determine Winner',
            'modal_size' => 'col-md-8 col-lg-4',
            'body' => '<!-- handlebars will handle this part -->',
            'body_string' => true,
            'footer' => '<button class="btn btn-success btn-claim-win" type="button"></button>
                        <button class="btn btn-primary btn-claim-loss" type="button"></button>',
            'footer_string' => true,
        ]); ?>

        <?php $CI->load->view('partials/_donation_modals'); ?>
    <?php endif; ?>


    <?php if ($CI->user): ?>
    <div class="addresses-container hide" data-address-container-from="hidden" id="hidden-addresses">
        <?php $CI->load->view('partials/_addresses'); ?>
    </div>
    <?php endif; ?>

    <?php $CI->modal('citizen-admin-modal', [
        'header' => 'Citizen Audit Data',
        'modal_size' => 'col-md-6',
        'body' => '',
        'body_string' => true,
    ]); ?>

    <div class="modal fade gh-modal-style-2"
         id="giverhub-petition-publish-success-modal"
         tabindex="-1"
         role="dialog"
         aria-labelledby="giverhub-petition-publish-success-modal"
         aria-hidden="true"></div>
    <div class="modal fade gh-modal-style-2"
         id="giverhub-petition-preview-modal"
         tabindex="-1"
         role="dialog"
         aria-labelledby="giverhub-petition-preview-modal"
         aria-hidden="true"></div>

    <?php $GLOBALS['super_timers']['eeee_final'] = microtime(true) - $GLOBALS['super_start']; ?>
    <?php if (true || GIVERHUB_DEBUG): ?>
        <?php foreach($GLOBALS['super_timers'] as $timer_name => $value): ?>
            <!-- super_timer: <?php echo $timer_name; ?> : <?php echo $value; ?> -->
        <?php endforeach; ?>
        <?php
            $site_speed = new \Entity\SiteSpeed();
            if ($CI->user) {
                $site_speed->setUserId($CI->user->getId());
            }
            $site_speed->setSpeed(microtime(true) - $GLOBALS['super_start']);
            $site_speed->setExtra(json_encode($GLOBALS['super_timers']));
            $site_speed->setDate(new \DateTime());
            $site_speed->setUrl($_SERVER['REQUEST_URI']);
            \Base_Controller::$em->persist($site_speed);
            \Base_Controller::$em->flush($site_speed);
        ?>
    <?php endif; ?>

    <?php $CI->modal('share-petition-modal', [
        'header' => 'Share Petition',
        'modal_size' => 'col-md-4',
        'body' => '/modals/share-petition-modal-body',
        'body_string' => false,
    ]); ?>

    <?php if ($this->user): ?>
        <?php $CI->modal('sign-petition-modal', [
            'header' => 'Success!',
            'modal_size' => 'col-md-3',
            'body' => '',
            'body_string' => true,
        ]); ?>
    <?php endif; ?>
    <script>
        window.twttr=(function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],t=window.twttr||{};if(d.getElementById(id))return;js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);t._e=[];t.ready=function(f){t._e.push(f);};return t;}(document,"script","twitter-wjs"));
    </script>
</body>
</html>
