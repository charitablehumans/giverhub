<?php $CI =& get_instance(); ?>
<p class="lead txtCntr">Create a giverhub account and start donating</p>

<div id="google-register-form" class="well reg-form">
    <a
        class="btn gh-trigger-event"
        data-event-category="button"
        data-event-action="click"
        data-event-label="Register using google (signup-modal-google)"
        href="<?php echo $CI->googleAuthUrl; ?>">Register using google (this will take you to
        google).</a>
</div>