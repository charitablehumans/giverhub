<?php
/** @var \Base_Controller $CI */
$CI =& get_instance();
$CI->modal('vol-msg-modal', [
    'header' => 'Volunteer Message',
    'body' => '/volunteering/_message_modal_body',
    'body_string' => false,
    'footer' => '',
    'modal_size' => 'col-md-3',
]);