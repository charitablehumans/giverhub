<?php
/** @var \Entity\Badge[] $badges */
/** @var \Base_Controller $CI */
$CI =& get_instance();
$CI->modal('all-my-badges-modal', [
    'header' => 'Badges',
    'modal_size' => 'col-md-6 col-md-offset-3',
    'body' => '/modals/all-my-badges-modal-body',
    'body_data' => ['badges' => $badges],
    'body_string' => false,
    'footer' => '<button type="button" class="btn btn-primary" data-dismiss="modal">CLOSE</button>',
    'footer_string' => true,
]);