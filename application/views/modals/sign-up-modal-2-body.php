<?php $CI =& get_instance(); ?>
<a href="#"
    class="sign_up_fb facebook-sign-in gh-trigger-event"
    data-event-category="button"
    data-event-action="click"
    data-event-label="Facebook sign up (sign-up-modal-2)">
    Join with Facebook
</a>
<a
    href="<?php echo $CI->getGoogleUrl('/'.(uri_string()?uri_string():'')); ?>"
    class="sign_up_google google-sign-in gh-trigger-event"
    data-event-category="button"
    data-event-action="click"
    data-event-label="Google sign up (sign-up-modal-2)">
    Join with Google
</a>

<div class="gh_lightbox_separator"><span>or</span></div>
<form class="input-container" method="post" id="sign-up-modal-2-sign-up-input-container">
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

    <button id="sign-up-modal-2-signup-button"
            type="button"
            class="btn-submit-signup btn btn-primary cntr gh-trigger-event"
            data-signup-message="modal"
            data-event-category="button"
            data-event-action="click"
            data-loading-text="Join"
            data-target-form="#sign-up-modal-2-sign-up-input-container"
            data-event-label="sign-up (sign-in-modal-2)">Join</button>
</form>