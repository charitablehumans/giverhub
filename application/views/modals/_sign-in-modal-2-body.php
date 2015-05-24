<?php $CI =& get_instance(); ?>
<a
    href="#"
    class="sign_up_fb facebook-sign-in gh-trigger-event"
    data-event-category="button"
    data-event-action="click"
    data-event-label="Facebook sign in (sign-in-modal-2)">
    Sign In with Facebook
</a>
<a
    href="<?php echo $CI->getGoogleUrl('/'.(uri_string()?uri_string():'')); ?>"
    class="sign_up_google google-sign-in gh-trigger-event"
    data-event-category="button"
    data-event-action="click"
    data-event-label="Google sign in (sign-in-modal-2)">
    Sign In with Google
</a>
<div class="gh_lightbox_separator"><span>or</span></div>
<div class="input-container" id="sign-in-modal-2-sign-in-input-container">
    <input type="text"
           data-signin-button="#sign-in-modal-2-signin-button"
           class="form-control sign_username"
           placeholder="email or username">

    <input type="password"
           data-signin-button="#sign-in-modal-2-signin-button"
           class="form-control sign_pass"
           placeholder="password">

    <button id="sign-in-modal-2-signin-button"
            type="button"
            class="btn btn-primary btn-submit-login cntr gh-trigger-event"
            data-loading-text="Sign in"
            data-event-category="button"
            data-event-action="click"
            data-event-label="sign-in (sign-in-modal-2)"
            data-input-container="#sign-in-modal-2-sign-in-input-container">Sign In</button>
</div>