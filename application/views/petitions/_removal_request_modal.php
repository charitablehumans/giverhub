<?php
/** @var \Base_Controller $CI */
$CI =& get_instance();
$CI->modal('removal-request-modal', [
    'header' => 'Signature Removal Request',
    'modal_size' => 'col-md-4',
    'body' => '<p class="lead txtCntr address-lead">This signature is displayed publicly on <a href="http://change.org" target="_blank">change.org</a>, making it anonymous will only hide the signature on GiverHub, if you would like to hide your signature on change.org, you must do that through their site.</p>

                <div class="form-group">
                    <textarea class="form-control reason" placeholder="Enter a reason for hiding the signature..." tabindex="1"></textarea>
                </div>',
    'body_string' => true,
    'footer' => '<button type="button" class="btn btn-primary btn-submit-removal-request" data-loading-text="SUBMIT" tabindex="2">SUBMIT</button>',
    'footer_string' => true,
]);
