<?php
/** @var \Base_Controller $CI */
$CI =& get_instance();
if ($CI->donation_modals_included_already) {
    return;
}
$GLOBALS['super_timers']['dmdm1'] = microtime(true) - $GLOBALS['super_start'];
$CI->donation_modals_included_already = true;
$CI->modal('change-payment', [
    'header' => 'Payment Method',
    'modal_size' => 'col-md-6',
    'body' => '/modals/change-payment-modal-body',
    'body_string' => false,
    'body_wrapper' => false,
    'footer' => '<a href="#" class="btn btn-cancel-change-payment">Cancel</a>
                 <a href="#" id="btn-add-new-card" class="btn btn-primary" aria-hidden="true" data-loading-text="Adding...">Add New Card</a>',
    'footer_string' => true,
]);
$GLOBALS['super_timers']['dmdm2'] = microtime(true) - $GLOBALS['super_start'];
$CI->modal('instant-donations-confirmation-modal', [
    'header' => 'Instant Donations',
    'modal_size' => 'col-md-4 col-md-offset-4',
    'body' => '<p>You\'ve activated Instant Donations! Now whenever you enter a donation amount and click "Donate" the donation will go through instantly.</p>
                <label for="dont-show-instant-confirmation-again">Don\'t Show This Again <input type="checkbox" id="dont-show-instant-confirmation-again"></label>',
    'body_string' => true,
    'footer' => '<a href="#" class="btn btn-primary btn-close-instant-donation-confirmation">Ok</a>',
    'footer_string' => true,
]);
$GLOBALS['super_timers']['dmdm3'] = microtime(true) - $GLOBALS['super_start'];
$CI->modal('lbox_donations', [
    'header' => 'Donate',
    'extra_attributes' => 'data-givercoin-reward="'.\Entity\GiverCoin::findOneBy(['event' => 'donation'])->getAmount().'"',
    'modal_size' => 'col-md-6 col-md-offset-3 modal-dialog donation-modal',
    'body' => '/modals/lbox_donations-body',
    'body_string' => false,
    'body_wrapper' => false,
]);
$GLOBALS['super_timers']['dmdm4'] = microtime(true) - $GLOBALS['super_start'];
$CI->modal('lbox_wrong_amount', [
    'header' => 'Wrong Amount',
    'body' => '<span class="lead txtCntr">
					<h4>WRONG AMOUNT</h4>
					Amount must be over <strong>$10</strong>, please enter plain digits <br/>
					without spaces, punctuations or dollar signs.
				</span>',
    'body_string' => true,
    'footer' => '<div class="row">
                    <button type="button" class="btn btn-primary btn-continue-after-wrong-amount cntr">CONTINUE</button>
                </div>',
    'footer_string' => true,
]);
$GLOBALS['super_timers']['dmdm5'] = microtime(true) - $GLOBALS['super_start'];
$CI->modal('givercardDonationSucessMsgModal', [
    'header' => 'Woohoo!',
    'modal_size' => 'col-md-6 col-md-offset-3 modal-dialog',
    'body' => '<div class="modal-body clearfix">
                    <p class="reward">Woohoo! Your donation was successful!<br/><br/>Thank you for donating $<span class="donation-confirmation-amount"></span> to <span id="donation-confirmation-name"></span>!<br></p>
                </div>',
    'body_string' => true,
]);
$GLOBALS['super_timers']['dmdm6'] = microtime(true) - $GLOBALS['super_start'];
if ($CI->user) {
    $CI->load->view('partials/_address-modal');
}
$GLOBALS['super_timers']['dmdm7'] = microtime(true) - $GLOBALS['super_start'];