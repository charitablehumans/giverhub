<?php
/** @var \Base_Controller $CI */
$CI =& get_instance();
if (!$CI->user) {
    throw new Exception('Does not make sense to load this modal when user is not signed in.');
}
$CI->modal('address-modal', [
    'header' => 'Add Address',
    'modal_size' => 'col-md-5',
    'body' => '/modals/address-modal-body',
    'body_string' => false,
    'footer' => '<button id="btn-save-address" type="button" class="btn btn-primary" data-loading-text="SAVING..." tabindex="10">SAVE</button>',
    'footer_string' => true,
]);
