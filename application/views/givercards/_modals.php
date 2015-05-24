<?php
/** @var \Base_Controller $CI */
$CI =& get_instance();
$fromUserName = $this->user->getFname().' '.$this->user->getLname();

$CI->modal('giver-card-review-modal', [
    'header' => 'Givercard',
    'modal_size' => 'col-md-6 col-md-offset-3 modal-dialog',
    'body' => '<div class="col-md-12">
                    <p>
                        <span class="color_light">	PAYMENT METHOD: </span>
                        <div>
                            <span id="givercard-donation-method" class="value vegur_regular">
                                <span class="givercard-donation-method-state" id="givercard-donation-method-loading">Loading...</span>
                                <span class="givercard-donation-method-state" id="givercard-donation-method-empty"><a id="btn-add-payment-method-modal-givercard" href="#">Add</a></span>
                                <span class="givercard-donation-method-state" id="givercard-donation-method-card"></span>
                            </span>
                        </div>
                    </p>
                    <div class="gh_lightbox_separator"></div>

                    <p>
                        This is how your GiverCard will appear to <span class="givercard_recipient_fname"></span><br/>
                    </p>
                    <p class="givercard-review-desc">
                        Hi <span class="givercard_recipient_fname"></span>, '.htmlspecialchars($fromUserName).' has given you a GiverCard worth $<span class="givercard_amount"></span>! A GiverCard is a type of e-gift card that enables you to donate to any of the 2 million nonprofits in GiverHub\'s database, which is nearly EVERY nonprofit in the US! In other words, instead of donating to a nonprofit on your behalf, '.$this->user->getFname().' is letting YOU choose which nonprofit, or nonprofits, you want to donate to with THEIR money!
                    </p>
                    <p class="givercard-review-desc">
                        Before you start donating, please read this brief message from '.$this->user->getFname().': <br/><p class="givercard_message gh_spacer_7"></p>
                    </p>

                    <div class="givercard_information">
                        <span class="givercard_message"></span>
                        <span class="givercard_amount"></span>
                        <span class="existing_recipient_details"></span>
                        <span class="new_recipient_details"></span>
                        <span class="new_recipient_name"></span>
                        <span class="existing_recipient_id"></span>
                        <span class="fb_user_details"></span>
                        <span class="fb_user_id"></span>
                    </div>

                    <p class="pull-right">OK, this looks good<button type="button" class="btn btn-success btn-giver-card-confirm">Send</button></p>
				</div>',
    'body_string' => true,
    'footer' => '<button type="button" class="btn-save-charity-admin-data btn btn-primary" data-loading-text="SAVE" tabindex="3">SAVE</button>',
    'footer_string' => true,
]);

$CI->modal('giver-card-success-msg-modal', [
    'header' => 'Givercard',
    'modal_size' => 'col-md-6 col-md-offset-3 modal-dialog',
    'body' => '',
    'body_string' => true,
    'footer' => '<p class="reward">You have Successfully sent a GiverCard!</p>',
    'footer_string' => true,
]);

if ($CI->user) {
    $CI->load->view('partials/_donation_modals');
}
