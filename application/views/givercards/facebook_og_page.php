<?php
/** @var \Base_Controller $CI */
$CI =& get_instance();
?>
<section class="gh_secondary_header clearfix">

    <section class="container empty_header">

        <div class="row">
            <h2 class="col-md-6">
                GiverCard
                <small class="blk"></small>
            </h2>

        </div><!-- row end -->

    </section><!-- empty_header end -->
</section>
<main class="" role="main">
    <section class="filter clearfix">
        <section class="container">
            <h1 class="vegur_light">GiverCard</h1>
        </section>
    </section>

    <section class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="block bet-info">
                   <?php $CI->load->view('givercards/_givercard_info', ['givercard' => $givercard]); ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="block">
                    <?php if ($CI->user): ?>
                        <?php if ($CI->user->getId() == $givercard->getFromUserId()): ?>
                            This GiverCard was created by you. You can view it in your Sent GiverCard's list.<br/>
                            Click <a href="/giver-cards">here</a>
                        <?php elseif (!$givercard->isFacebookFriend() && $CI->user->getId() == $givercard->getToUserId()): ?>
                            This GiverCard was for you. You can view it in your Received GiverCard's list.<br/>
                            Click <a href="/giver-cards">here</a>
                        <?php elseif ($givercard->isFacebookFriend() && $CI->user->getFbUserId() && $CI->user->getFbUserId() == $givercard->getTo()->getFbId()): ?>
                            This GiverCard was for you. You can view it in your Received GiverCard's list.<br/>
                            Click <a href="/giver-cards">here</a>
                        <?php else: ?>
                            This GiverCard is not related to you.
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="landing_page_header container gh_spacer_14">
                            <div class="row">

                                <!-- Login box -->
                                <div class="clearfix">
                                    <div class="col-md-12 landing-page-heading gh_spacer_14 vegur_light">Log in</div>

                                    <div class="col-md-5 col-sm-10 col-xs-5 txtCntr gh_spacer_14 sign_in_buttons">
                                        <a class="fb_btn_new pull-left gh_spacer_14 facebook-sign-in gh-trigger-event"
                                           data-event-category="button"
                                           data-event-action="click"
                                           data-event-label="Facebook sign in (givercard-fb-landing-page)"
                                           href="#"></a>
                                        <a href="<?php echo $CI->getGoogleUrl('/'.(uri_string()?uri_string():'')); ?>"
                                           data-event-category="button"
                                           data-event-action="click"
                                           data-event-label="Google sign in (givercard-fb-landing-page)"
                                           class="gmail_btn_new pull-left google-sign-in gh-trigger-event"></a>
                                    </div>

                                    <div id="givercard-fb-landing-page-signin-input-container"
                                         class="col-md-6 col-sm-10 col-xs-6 txtCntr gh_spacer_14 vegur_light sign_in_buttons">
                                        <input type="text"
                                               data-signin-button="#givercard-fb-landing-page-signin-signin-button"
                                               placeholder="username"
                                               class="sign_username form-control gh_spacer_14">
                                        <input type="password"
                                               data-signin-button="#givercard-fb-landing-page-signin-signin-button"
                                               placeholder="password"
                                               class="sign_pass form-control gh_spacer_7">

                                        <a data-target="#forgot-password-modal" data-dismiss="modal" data-toggle="modal" href="#" class="pull-left">Forgot Password?</a>
                                        <button
                                            id="givercard-fb-landing-page-signin-signin-button"
                                            data-input-container="#givercard-fb-landing-page-signin-input-container"
                                            data-event-category="button"
                                            data-event-action="click"
                                            data-event-label="sign-in (givercard-fb-landing-page)"
                                            type="button"
                                            class="btn btn-primary btn-submit-login noise pull-right gh-trigger-event">SIGN IN</button>
                                    </div>

                                    <div id="signin-message-container" class="hide alert alert-danger">
                                        <span id="signin-message-incorrect"><strong>Sorry.</strong> Incorrect username/password.</span>
                                        <span id="signin-message-unconfirmed"><strong>You need to confirm your email.</strong> Check your inbox and spam folder too.			</span>
                                    </div>

                                </div>
                                <!-- End of login box -->
                                <hr/>
                                <!-- Sign Up box -->
                                <div class="clearfix">
                                    <div class="col-md-12 landing-page-heading gh_spacer_14 vegur_light">Sign Up</div>
                                    <form id="signup-form-from-landing-page" class="signup-form" action="#">

                                        <div class="col-md-5 col-sm-10 col-xs-5 txtCntr gh_spacer_14 sign_in_buttons">
                                            <a href="#" data-toggle="modal" data-dismiss="modal" data-target="#signup-modal-facebook"
                                               class="sign_up_fb gh_spacer_14"></a>

                                            <a href="#" data-toggle="modal" data-dismiss="modal" data-target="#signup-modal-google"
                                               class="sign_up_gmail pull-left">Sign in with google</a>
                                        </div>

                                        <div class="col-md-6 col-sm-10 col-xs-6 vegur_light sign_in_buttons">

                                            <input <?php if (CLOSED_BETA): ?>disabled="disabled"<?php endif; ?> type="text" class="form-control gh_spacer_14" id="join_name" placeholder="Name" name="name">

                                            <input <?php if (CLOSED_BETA): ?>disabled="disabled"<?php endif; ?> type="text" class="form-control gh_spacer_14" id="join_username" placeholder="Username"
                                                   name="username">

                                            <input <?php if (CLOSED_BETA): ?>disabled="disabled"<?php endif; ?> type="email" class="form-control gh_spacer_14" id="join_email" placeholder="Email"
                                                   name="email">

                                            <input <?php if (CLOSED_BETA): ?>disabled="disabled"<?php endif; ?> type="password" class="form-control gh_spacer_14" id="join_pass" placeholder="Password"
                                                   name="password">

                                            <input <?php if (CLOSED_BETA): ?>disabled="disabled"<?php endif; ?> type="password" class="form-control gh_spacer_14" id="join_pass_repeat" placeholder="Repeat Password"
                                                   name="password2">

                                            <div class="hide alert signup-message-container">
                                                <span class="signup-message"></span>
                                            </div>
                                            <div class="form-group">
                                                <button data-loading-text="Creating..." type="button" <?php if (CLOSED_BETA): ?>disabled="disabled"<?php endif; ?> data-target-form="#signup-form-from-landing-page"
                                                        class="btn btn-primary btn-submit-signup noise cntr">
                                                    <?php if (CLOSED_BETA): ?>
                                                        N/A DURING CLOSED BETA
                                                    <?php else: ?>
                                                        CREATE ACCOUNT
                                                    <?php endif; ?>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <?php if (isset($_GET['fb-givercard'])): ?>
                            <div class="trigger-fb-sign-in hide"></div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
</main>
