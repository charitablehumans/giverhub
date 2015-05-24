<?php
/** @var \Base_Controller $CI */
$CI =& get_instance();
/** @var \Entity\Bet $bet */
?>
<section class="gh_secondary_header clearfix">

    <section class="container empty_header">

        <div class="row">
            <h2 class="col-md-6">
                Bet-a-Friend
                <small class="blk"></small>
            </h2>

        </div><!-- row end -->

    </section><!-- empty_header end -->
</section>
<main class="" role="main">
    <section class="filter clearfix">
        <section class="container">
            <h1 class="vegur_light">Bet - <?php echo htmlspecialchars($bet->getName()); ?></h1>
        </section>
    </section>

    <section class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="block bet-info">
                    <?php $CI->load->view('bets/_bet_info', ['bet' => $bet]); ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="block">
                    <p>
                        <?php if ($bet->isOpen()): ?>
                            <?php if ($CI->user): ?>
                                This bet is open.
		                        <?php if ($bet->getUser() != $CI->user && !$bet->isToUser($CI->user)): ?>
			                        <button data-bet-id="<?php echo $bet->getId(); ?>"
			                                class="btn btn-lg btn-success btn-accept-open-bet"
			                                data-loading-text="JOIN THE BET"
			                                type="button">JOIN THE BET</button>
		                        <?php endif; ?>
                            <?php else: ?>
					            This bet is open.
					            <button
					                data-toggle="modal" data-target="#signin-or-join-first-modal"
					                class="btn btn-lg btn-success"
					                type="button">JOIN THE BET</button>
                            <?php endif; ?>
                        <?php else: ?>
                            This bet is not open. Only invited people can accept the bet.
                        <?php endif; ?>
                    </p>
                    <?php if ($CI->user): ?>
                        <?php if ($CI->user == $bet->getUser()): ?>
                            <p>This bet was created by you. You can view it in your <a href="/members/bets">bet list</a> too.</p>
                        <?php elseif ($bet->isToUser($CI->user)): ?>
                            <p>You are in this bet. You can view it in your <a href="/members/bets">bet list</a> too.</p>
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
                                           data-event-label="Facebook sign in (bet-fb-landing-page)"
                                           href="#"></a>
                                        <a href="<?php echo $CI->getGoogleUrl('/'.(uri_string()?uri_string():'')); ?>"
                                           class="gmail_btn_new pull-left google-sign-in gh-trigger-event"
                                           data-event-category="button"
                                           data-event-action="click"
                                           data-event-label="Google sign in (bet-fb-landing-page)"></a>
                                    </div>

                                    <div id="bet-fb-landing-page-sign-in-input-container"
                                         class="col-md-6 col-sm-10 col-xs-6 txtCntr gh_spacer_14 vegur_light sign_in_buttons">
                                        <input type="text"
                                               placeholder="username"
                                               data-signin-button="#bet-fb-landing-page-sign-in-sign-in-button"
                                               class="sign_username form-control gh_spacer_14">
                                        <input type="password"
                                               placeholder="password"
                                               data-signin-button="#bet-fb-landing-page-sign-in-sign-in-button"
                                               class="sign_pass form-control gh_spacer_7">

                                        <a data-target="#forgot-password-modal" data-dismiss="modal" data-toggle="modal" href="#" class="pull-left">Forgot Password?</a>
                                        <button
                                            id="bet-fb-landing-page-sign-in-sign-in-button"
                                            type="button"
                                            class="btn btn-primary btn-submit-login noise pull-right gh-trigger-event"
                                            data-event-category="button"
                                            data-event-action="click"
                                            data-event-label="sign-in (bet-fb-landing-page)"
                                            data-input-container="#bet-fb-landing-page-sign-in-input-container">SIGN IN</button>
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
                                                <button data-loading-text="Creating..."
                                                        type="button"
                                                        data-target-form="#signup-form-from-landing-page"
                                                        class="btn btn-primary btn-submit-signup noise cntr">CREATE ACCOUNT</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <?php if (isset($_GET['fb-bet'])): ?>
                            <div class="trigger-fb-sign-in hide"></div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
	            <?php $CI->load->view('/partials/_fun_donations'); ?>
            </div>
        </div>
    </section>
</main>

<?php if ($CI->user): ?>
    <?php $CI->load->view('/partials/_donation_modals'); ?>
<?php endif; ?>
<?php $CI->load->view('/bets/_modals'); ?>