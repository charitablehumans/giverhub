<?php
/** @var \Base_Controller $CI */
$CI =& get_instance();

/** @var string $current_text */
/** @var array $views */
?>

<section class="gh_secondary_header clearfix new-startpage_header">
    <section class="container slideshow_content landing-page-info">
        <div class="clearbothnow">
            <h1 class="new-startpage_header-h1 vegur_light">Welcome to Giverhub: The Future of Giving</h1>
			<h3 class="new-startpage_header-h3 vegur_light">The easiest (and funnest) way to donate<br/>and keep track of your donations, period.</h3>
            <a class="btn btn-lg btn-primary" href="/search">Start Searching Nonprofits and Petitions</a>
        </div>
    </section>
</section>

<main class="members_main landing_page_main" role="main" id="main_content_area">

	
	<div class="landing_page_header container gh_spacer_14">
	    <div class="row gh_spacer_21 landing_page_header_inner">

			<!-- Login box -->
			<div class="col-md-6 col-sm-6 col-xs-12 content_divider clearfix landing_page_login_box">
				<div class="col-md-12 landing-page-heading gh_spacer_14 vegur_light login_title">Log in</div>

				<div class="col-md-5 col-sm-10 col-xs-5 txtCntr gh_spacer_14 sign_in_buttons">
	                <a class="fb_btn_new pull-left gh_spacer_14 facebook-sign-in gh-trigger-event"
                       href="#"
                        data-event-category="button"
                        data-event-action="click"
                        data-event-label="Facebook sign in (landing page)"></a><a
                        href="<?php echo $CI->getGoogleUrl('/'.(uri_string()?uri_string():'')); ?>"
                        class="gmail_btn_new pull-left google-sign-in gh-trigger-event"
                        data-event-category="button"
                        data-event-action="click"
                        data-event-label="Google sign in (landing page)"></a>
	            </div>

	            <div id="landing-page-signin-input-container" class="col-md-6 col-sm-10 col-xs-6 txtCntr gh_spacer_14 vegur_light sign_in_buttons">
	                <input type="text"
                           data-signin-button="#landing-page-signin-signin-button"
                           placeholder="username"
                           class="sign_username form-control gh_spacer_14">
					<input type="password"
                           data-signin-button="#landing-page-signin-signin-button"
                           placeholder="password"
                           class="sign_pass form-control gh_spacer_7">

					<a data-target="#forgot-password-modal" data-dismiss="modal" data-toggle="modal" href="#" class="forgot-password-button pull-left">Forgot Password?</a>
					<button
                        type="button"
                        id="landing-page-signin-signin-button"
                        class="btn btn-primary btn-submit-login noise pull-right gh-trigger-event"
                        data-event-category="button"
                        data-event-action="click"
                        data-event-label="sign-in (landing page)"
                        data-input-container="#landing-page-signin-input-container">SIGN IN</button>
	            </div>

				<div id="signin-message-container" class="hide alert alert-danger">
	                <span id="signin-message-incorrect"><strong>Sorry.</strong> Incorrect username/password.</span>
	                <span id="signin-message-unconfirmed"><strong>You need to confirm your email.</strong> Check your inbox and spam folder too.			</span>
	            </div>

			</div>
			<!-- End of login box -->

			<!-- Take a tour area -->
	        <div class="col-md-6 col-sm-6 col-xs-12 txtCntr">
				<div class="col-md-12 gh_spacer_14">
					<div class="col-md-12 vegur_light landing-page-top-right-heading">Find, learn about, and donate to nonprofits in seconds</div>
					<div class="col-md-12 gh_spacer_7 vegur_light landing-page-top-right-sub-heading">Enter search terms below to take GiverHub for a spin</div>
					<div class="landing-page-nonprofits-box">
                        <?php $this->load->view('/members/_nonprofit_feed', ['user' => null]); ?>
                    </div>
				</div>
			</div>
			<!-- End of take a tour area -->
	    </div>
	</div>


	<div class="landing-page-sign-up-box clearfix">
		<div class="landing-page-tour-box txtCntr">
			<button class="btn sample btn-lg btn-sample take-a-tour-btn vegur_light allblur gh-trigger-event"
                    data-event-category="button"
                    data-event-action="click"
                    data-event-label="take a tour (landing page)">Take a tour</button>
		</div>
		<div class="container gh_spacer_14">
			<div class="row">
				<!-- Sign Up box -->
				<div class="col-md-6 col-sm-6 col-xs-12 clearfix content_divider sign-up-box">
					<div class="col-md-12 landing-page-heading gh_spacer_14 vegur_light lp-sign-up gh-trigger-event"
                         data-event-category="button"
                         data-event-action="click"
                         data-event-label="Sign Up (landing page)">Sign Up</div>
					<form id="signup-form-from-landing-page" class="signup-form" action="#">

						<div class="col-md-5 col-sm-10 col-xs-5 txtCntr gh_spacer_14 sign_in_buttons">
                            <a
                                href="#"
                                data-toggle="modal"
                                data-dismiss="modal"
                                data-target="#signup-modal-facebook"
                                class="sign_up_fb gh_spacer_14 gh-trigger-event"
                                data-event-category="button"
                                data-event-action="click"
                                data-event-label="facebook-sign-up (landing page)"></a><a
                                href="#"
                                data-toggle="modal"
                                data-dismiss="modal"
                                data-target="#signup-modal-google"
                                class="sign_up_gmail pull-left gh-trigger-event"
                                data-event-category="button"
                                data-event-action="click"
                                data-event-label="google-sign-up (landing page)">Sign in with google</a>
						</div>

			            <div class="col-md-6 col-sm-10 col-xs-6 vegur_light sign_in_buttons">
                            <input type="text" class="form-control gh_spacer_14" id="join_name" placeholder="Name" name="name">
                            <input type="text" class="form-control gh_spacer_14" id="join_username" placeholder="Username" name="username">
                            <input type="email" class="form-control gh_spacer_14" id="join_email" placeholder="Email" name="email">
                            <input type="password" class="form-control gh_spacer_14" id="join_pass" placeholder="Password" name="password">
                            <input type="password" class="form-control gh_spacer_14" id="join_pass_repeat" placeholder="Repeat Password" name="password2">

							<div class="hide alert signup-message-container">
								<span class="signup-message"></span>
							</div>
							<div class="form-group">
								<button
                                    data-loading-text="Creating..."
                                    type="button"
                                    <?php if (CLOSED_BETA): ?>disabled="disabled"<?php endif; ?>
                                    data-target-form="#signup-form-from-landing-page"
                                    class="btn btn-primary btn-submit-signup noise cntr gh-trigger-event"
                                    data-event-category="button"
                                    data-event-action="click"
                                    data-event-label="Create Account (landing page)">
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
				<!-- End of sign Up box -->

				<!-- Bet a friend static area -->
				<div class="col-md-6 col-sm-6 col-xs-12 txtCntr">
					<div class="col-md-12 gh_spacer_21 home-page-bet-friend-text vegur_light">
						Giverhub lets you donate to almost any nonprofit, but it also features new and fun ways of donating like Bet-a-Friend
                    </div>
					<div class="col-md-12">
						<div class="block bet-a-friend bet-a-friend-landing-page">
							<div class="numero3"></div>
			                <div class="row">
			                    <div class="col-md-12">
			                        <h3 class="gh_block_title home-page-bet-a-friend-title vegur_light">Bet-a-Friend</h3>
			                    </div>
			                    <div class="col-md-12">
			                        <div class="photo_grid grid_description vegur_light bet-a-friend-text">
			                            <p>Know you're right? Make a bet. Winner decides which charity the money goes to.</p>
			                            <div class="start-bet-container">
			                                <div class="col-md-12 col-sm-12 col-xs-12 vegur_light">
			                                    <a href="#" class="landing-page-learn-more-bet landing-page-bet-friend-modal">Learn more about making a bet</a>
			                                    <a href="#" class="start-bet-btn-lp btn btn-success pull-right landing-page-bet-friend-modal">START</a>
			                                    <span class="start-making-bet pull-right">Start making a bet!</span>
			                                </div>
			                            </div>
			                            <div class="bet-list-container"></div>
			                        </div>
			                    </div>
			                </div>
			            </div>
					</div>
				</div>
				<!-- End of bet a friend static area -->
			</div>
        </div>
	</div>
</main>
<!-- End of landing page Content Area -->

<?php $CI->load->view('bets/_learn_about_modal'); ?>


<?php if ($CI->user) { $CI->load->view('partials/_donation_modals'); }
