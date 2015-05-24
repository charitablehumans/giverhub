<?php $CI =& get_instance(); ?>
<span class="lead txtCntr">
                        Sign in to sign petitions
                    </span>

<a
    href="#"
    class="sign_up_fb facebook-sign-in gh-trigger-event"
    data-event-category="button"
    data-event-action="click"
    data-event-label="Facebook sign in (signin-to-sign-petitions-modal)">
    <img src="/img/fb_sign_in_2.png" alt="Sign in to GiverHub with facebook">
</a>

<a
    href="<?php echo $CI->getGoogleUrl('/'.(uri_string()?uri_string():'')); ?>"
    class="sign_up_google google-sign-in gh-trigger-event"
    data-event-category="button"
    data-event-action="click"
    data-event-label="Google sign in (signin-to-sign-petitions-modal)">
    <img src="/img/google_sign_in_2.png" alt="Sign in to GiverHub with google">
</a>

<div class="gh_lightbox_separator"><span>or</span></div>

<div id="signin-to-sign-petitions-modal-input-container">
    <div class="form-group">
        <input type="text"
               data-signin-button="#signin-to-sign-petitions-modal-signin-button"
               class="form-control sign_username"
               placeholder="email or username">
    </div>

    <div class="">
        <input type="password"
               data-signin-button="#signin-to-sign-petitions-modal-signin-button"
               class="form-control sign_pass"
               placeholder="password">
    </div>
</div>