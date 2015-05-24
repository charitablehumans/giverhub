<?php $CI =& get_instance(); ?>
<p class="lead txtCntr">Create a giverhub account and start donating</p>
<?php $CI->session->set_userdata('sign_up_redir_url', uri_string()); ?>
<div id="facebook-register-form" class="reg-form">
    <iframe
        src="https://www.facebook.com/plugins/registration?client_id=<?php echo $CI->config->item(
            'fb_app_id'
        ); ?>&amp;redirect_uri=<?php echo urlencode(
            ($_SERVER['SERVER_PORT'] == 443 ? 'https' : 'http').'://' . $_SERVER['SERVER_NAME']
        ); ?>&amp;fields=name,birthday,gender,email"
        height="330">
    </iframe>
</div>