<?php $CI =& get_instance(); ?>
<?php $GLOBALS['super_timers']['lblb1'] = microtime(true) - $GLOBALS['super_start']; ?>

<div class="modal-body clearfix donation-modal-body">
    <div class="col-md-12 txtCntr clearfix donation_modal_guide_text">
        <span class="you-are-donating-to"></span> <br/><span class="donation_modal_donating_title color_title charity-name">SOMETHING IS WRONG</span>
    </div>
    <div class="gh_slider_container">
        <!-- DONATE -->
        <div id="slider1">
            <div class="donation_modal_amount_options">
                <!--<h5 class="color_title charity-name">SOMETHING IS WRONG!</h5>-->
                <div class="hide-on-xs-resolution donation_modal_guide_text">Quick-Select Your Donation</div>
                <ul id="donation-amount-list" class="list-unstyled list-inline clearfix gh_ammount text-center">
                    <li><a class="donation-modal-amount-list-item" href="#" data-amount="25">$25</a></li>
                    <li><a class="donation-modal-amount-list-item active default" href="#" data-amount="75">$75</a></li>
                    <li><a class="donation-modal-amount-list-item" href="#" data-amount="100">$100</a></li>
                </ul>
            </div>

            <div class="donation_modal_guide_text hide-on-xs-resolution">Use one of the Quick-Select buttons above to automatically fill the donation field,<br/> or manually enter a donation below</div>

            <div class="donation_modal_amount_options">
                <form class="form-donate">
                    <div class="form-group text-center">
                        <div id="donation-modal-form-selected-amount">
                            <!--<label class="pull-left donation_modal_guide_text">Minimum Donation: $10</label>-->
                            <input type="text" class="clear-md amount form-control donation-modal-amount col-md-6" id="donate_amount" name="donate_amount" placeholder="Minimum Donation: $10" value="75">
                        </div>
                        <div id="donation-modal-form-txt-notice">
                            <small class="blk clearbothnow text-center color_light">*4.75% of this donation is paid to NetworkForGood as a fee for processing this payment. <a href="/faq" target="_blank">Click here</a> for more info on NetworkForGood.</small>
                        </div>
                    </div>
                </form>
            </div>

            <div class="slider-footer">
                <span class="gh_lightbox_separator"></span>
                <div class="clearfix">
                    <p class="text-center">
                        <a class="btn btn-primary btn-donation-modal-goto-step2">CONTINUE</a>
                        <?php if ($CI->user && $CI->user->isAdmin()): ?>
                    <div class="center">
                        Pressed continue
                        <?php $GLOBALS['super_timers']['lblb2'] = microtime(true) - $GLOBALS['super_start']; ?>
                        <?php $CI->load->view('/partials/stat', ['name' => 'donation-goto-step2']); ?>
                        <?php $GLOBALS['super_timers']['lblb3'] = microtime(true) - $GLOBALS['super_start']; ?>
                    </div>
                    <?php endif; ?>
                    </p>

                </div>

                <div class="donation_modal_guide_text">Next Step: Confirm Amount and Make Your Donation</div>
                <?php if ($CI->user && $CI->user->isAdmin()): ?>
                    <a href="#" onclick="jQuery('.donation-stats-admin').removeClass('hide');">Show stats</a>
                    <div class="donation-stats-admin center hide">
                        Impressions for donate using CC/PayPal without amount
                        <?php $GLOBALS['super_timers']['lblb4'] = microtime(true) - $GLOBALS['super_start']; ?>
                        <?php $CI->load->view('/partials/stat', ['name' => 'impression-donate-using-cc-paypal-button']); ?>
                        <?php $GLOBALS['super_timers']['lblb5'] = microtime(true) - $GLOBALS['super_start']; ?>
                        Hover CC without amount
                        <?php $CI->load->view('/partials/stat', ['name' => 'hover-donate-cc']); ?>
                        <?php $GLOBALS['super_timers']['lblb6'] = microtime(true) - $GLOBALS['super_start']; ?>
                        Hover PayPal without amount
                        <?php $CI->load->view('/partials/stat', ['name' => 'hover-donate-paypal']); ?>
                        <?php $GLOBALS['super_timers']['lblb7'] = microtime(true) - $GLOBALS['super_start']; ?>
                        CC without amount
                        <?php $CI->load->view('/partials/stat', ['name' => 'donate-without-amount-cc']); ?>
                        <?php $GLOBALS['super_timers']['lblb8'] = microtime(true) - $GLOBALS['super_start']; ?>
                        PayPal without amount
                        <?php $CI->load->view('/partials/stat', ['name' => 'donate-without-amount-paypal']); ?>
                        <?php $GLOBALS['super_timers']['lblb9'] = microtime(true) - $GLOBALS['super_start']; ?>
                        Impressions for donate using CC/PayPal with amount
                        <?php $CI->load->view('/partials/stat', ['name' => 'impression-donate-using-cc-paypal-button-with-amount']); ?>
                        <?php $GLOBALS['super_timers']['lblb10'] = microtime(true) - $GLOBALS['super_start']; ?>
                        Hover CC with amount
                        <?php $CI->load->view('/partials/stat', ['name' => 'hover-donate-with-amount-cc']); ?>
                        Hover PayPal with amount
                        <?php $CI->load->view('/partials/stat', ['name' => 'hover-donate-with-amount-paypal']); ?>
                        CC with amount
                        <?php $CI->load->view('/partials/stat', ['name' => 'donate-with-amount-cc']); ?>
                        PayPal with amount
                        <?php $CI->load->view('/partials/stat', ['name' => 'donate-with-amount-paypal']); ?>

                        Impressions for donate from search
                        <?php $CI->load->view('/partials/stat', ['name' => 'impression-donate-from-search']); ?>
                        Hover donate from search
                        <?php $CI->load->view('/partials/stat', ['name' => 'hover-donate-from-search']); ?>
                        Donate From Search
                        <?php $CI->load->view('/partials/stat', ['name' => 'donate-from-search']); ?>

                        Impressions for donate from volunteering opp / activity feed post
                        <?php $CI->load->view('/partials/stat', ['name' => 'impression-donate-from-charity-with-amount']); ?>
                        Hover donate from volunteering opp / activity feed post
                        <?php $CI->load->view('/partials/stat', ['name' => 'hover-donate-from-charity-with-amount']); ?>
                        Donate from volunteering opp / activity feed post
                        <?php $CI->load->view('/partials/stat', ['name' => 'donate-charity-with-amount']); ?>
                        <?php $GLOBALS['super_timers']['lblb11'] = microtime(true) - $GLOBALS['super_start']; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div><!-- slider1 end -->

        <div id="slider2">

            <table class="table donation-modal-second-screen">
                <tbody>
                <tr>
                    <td>
                        <div>Donation Amount</div>
                        <div class="donation-amount-displayed-in-step2 vegur_regular">SOMETHING IS WRONG</div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div>Payment Method</div>
                        <div id="step2-payment-method-div">
                            <div id="step2-payment-methods">
                                <label id="label-paypal"><input class="payment-method-radio" type="radio" name="payment-method" value="paypal">PayPal</label>
                                <label id="label-cc"><input class="payment-method-radio" type="radio" name="payment-method" value="cc">Credit Card</label>
                            </div>
                                            <span id="step2-donation-method" class="value vegur_regular hide">
                                                <span class="step2-donation-method-state" id="step2-donation-method-loading">Loading...</span>
                                                <span class="step2-donation-method-state" id="step2-donation-method-empty"><a id="btn-add-payment-method-modal" href="#">Add Credit Card</a></span>
                                                <span class="step2-donation-method-state" id="step2-donation-method-card"></span>
                                            </span>
                        </div>
                        <?php $GLOBALS['super_timers']['lblb13'] = microtime(true) - $GLOBALS['super_start']; ?>
                        <?php if ($CI->user && $CI->user->isAdmin()): ?>
                            Clicked "Add Credit Card"
                            <?php $CI->load->view('/partials/stat', ['name' => 'donation-add-credit-card-from-step2']); ?>
                        <?php endif; ?>
                        <?php $GLOBALS['super_timers']['lblb14'] = microtime(true) - $GLOBALS['super_start']; ?>
                    </td>
                </tr>

                <!-- Show below option if user has GiverCards through which he can donate -->
                <?php $GLOBALS['super_timers']['lblb15'] = microtime(true) - $GLOBALS['super_start']; ?>
                <?php $giverCards = $CI->user->getUserGiverCards(); ?>
                <?php $GLOBALS['super_timers']['lblb16'] = microtime(true) - $GLOBALS['super_start']; ?>
                <?php if ($giverCards): ?>
                    <tr>
                        <td>
                            <div><b>OR</b><br/><input type="checkbox" name="givercard_payment" id="givercard_payment"> Use GiverCard for Payment</div>
                            <div class="givercard_select_options">
                                <select name="giver_card_name" id="giver_card_name">
                                    <option value="">--Please Select--</option>

                                    <?php foreach ($giverCards as $giverCard) : ?>
                                        <option value="<?php echo $giverCard->getId();?>"><?php echo $giverCard->getMessage();?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
                <?php $GLOBALS['super_timers']['lblb18'] = microtime(true) - $GLOBALS['super_start']; ?>

                <tr id="recurring-tr" class="hide">
                    <td>
                        <div>Make this a recurring donation?</div>
                        <div>
                            <select id="recurring-donations-select">
                                <option value="NotRecurring">Not Recurring</option>
                                <option value="NotRecurring">-------------</option>
                                <option value="Monthly">Monthly</option>
                                <option value="Quarterly">Quarterly</option>
                                <option value="Annually">Annually</option>
                            </select>
                        </div>
                        <label id="recurring-notify-label" title="You can go to your settings page to change this later." data-placement="bottom" class="hide gh_tooltip">
                            Always notify me 24 hours before any recurring donation.
                            <input type="checkbox" id="recurring-notify-checkbox" checked="checked">
                        </label>
                    </td>
                </tr>

                <tr id="dedication-tr">
                    <td>
                        <input
                            class="form-control"
                            id="dedication_input"
                            type="text"
                            placeholder="Dedicate your donation to...">
                        <p id="dedication_chars"></p>
                    </td>
                </tr>
                </tbody>
            </table>


            <div class="slider-footer">
                <span id="step2-separator" class="gh_lightbox_separator"></span>
                <br/>

                <div class="clearfix">
                    <p class="text-center">
                        <a class="btn btn-primary btn-donation-modal-goto-step3" data-loading-text="PROCESSING...">MAKE THE DONATION!</a>
                    </p>
                </div>

                <div class="donation_modal_guide_text">*Clicking the button above is the final step in the donation process and doing so will cause the donation to be made. <br/> <span class="vegur_regular">Donations are non-refundable.</span></div>
            </div>
        </div>

        <div id="slider3">
            <div class="modal-body clearfix">
                <p class="reward">
                    Thanks for your donation! You will receive an email receipt from Network For Good shortly.<br/><br/>
                    People are more likely to donate to a nonprofit if they know you have!
                </p>
                <?php if (GIVERHUB_LIVE): ?>
                    <div class="share-donation-success-container">
                        <p><label><input class="checkbox-share-donation-giverhub" checked="checked" type="checkbox"> Share on GiverHub</label></p>
                        <p><label><input class="checkbox-share-donation-facebook" checked="checked" type="checkbox"> Share on Facebook</label></p>
                        <p><button type="button" class="btn btn-success btn-share-donation-success" data-loading-text="Share">Share</button></p>
                    </div>
                <?php else: ?>
                    <?php $GLOBALS['super_timers']['lblb19'] = microtime(true) - $GLOBALS['super_start']; ?>
                    <?php $this->load->view('/partials/share-buttons-1'); ?>
                <?php endif; ?>
            </div><!-- modal-body end -->
        </div><!-- slider3 end -->
    </div><!-- gh_slider_container end -->
</div>
<?php $GLOBALS['super_timers']['lblb20'] = microtime(true) - $GLOBALS['super_start']; ?>