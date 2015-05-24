<?php $CI =& get_instance(); ?>
<span class="lead txtCntr alert alert-danger">
                        Sign in to sign petitions
                    </span>


<div class="col-md-12">
    <div class="col-md-6">
        <h2>Not a member?</h2>
        <a
            href="#"
            class="sign_up_fb facebook-sign-in gh-trigger-event"
            data-event-category="button"
            data-event-action="click"
            data-event-label="Facebook sign up (sign-in-or-join-first-modal-2)">
            Join with Facebook
        </a>
        <a
            href="<?php echo $CI->getGoogleUrl('/'.(uri_string()?uri_string():'')); ?>"
            class="sign_up_google google-sign-in gh-trigger-event"
            data-event-category="button"
            data-event-action="click"
            data-event-label="Google sign up (sign-in-or-join-first-modal-2)">
            Join with Google
        </a>

        <div class="gh_lightbox_separator"><span>or</span></div>
        <form class="input-container" method="post" id="sign-in-or-join-first-modal-2-sign-up-input-container">
            <input type="text"
                   name="email"
                   class="form-control join_email"
                   placeholder="email">

            <input type="password"
                   name="password"
                   class="form-control join_pass"
                   placeholder="password">

            <input type="password"
                   name="password2"
                   class="form-control join_pass_2"
                   placeholder="confirm password">

            <input type="hidden" name="type" value="no-name">

            <button id="sign-in-or-join-first-modal-2-signup-button"
                    type="button"
                    class="btn-submit-signup btn btn-primary cntr gh-trigger-event"
                    data-signup-message="modal"
                    data-event-category="button"
                    data-event-action="click"
                    data-loading-text="Join"
                    data-target-form="#sign-in-or-join-first-modal-2-sign-up-input-container"
                    data-event-label="sign-up (sign-in-or-join-first-modal-2)">Join</button>
        </form>
    </div>
    <div class="col-md-6">
        <h2>Already a member?</h2>
        <a
            href="#"
            class="sign_up_fb facebook-sign-in gh-trigger-event"
            data-event-category="button"
            data-event-action="click"
            data-event-label="Facebook sign in (sign-in-or-join-first-modal-2)">
            Sign In with Facebook
        </a>
        <a
            href="<?php echo $CI->getGoogleUrl('/'.(uri_string()?uri_string():'')); ?>"
            class="sign_up_google google-sign-in gh-trigger-event"
            data-event-category="button"
            data-event-action="click"
            data-event-label="Google sign in (sign-in-or-join-first-modal-2)">
            Sign In with Google
        </a>
        <div class="gh_lightbox_separator"><span>or</span></div>
        <div class="input-container" id="sign-in-or-join-first-modal-2-sign-in-input-container">
            <input type="text"
                   data-signin-button="#sign-in-or-join-first-modal-2-signin-button"
                   class="form-control sign_username"
                   placeholder="email or username">

            <input type="password"
                   data-signin-button="#sign-in-or-join-first-modal-2-signin-button"
                   class="form-control sign_pass"
                   placeholder="password">

            <button id="sign-in-or-join-first-modal-2-signin-button"
                    type="button"
                    class="btn btn-primary btn-submit-login cntr gh-trigger-event"
                    data-loading-text="Sign in"
                    data-event-category="button"
                    data-event-action="click"
                    data-event-label="sign-in (sign-in-or-join-first-modal-2)"
                    data-input-container="#sign-in-or-join-first-modal-2-sign-in-input-container">Sign In</button>
        </div>
    </div>
</div>