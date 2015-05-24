<?php
$CI =& get_instance();
$CI->modal('high-low-markers-modal', [
    'header' => 'High/low Markers',
    'modal_size' => 'col-md-5',
    'body' => '/modals/high-low-markers-modal-body',
    'body_string' => false,
]);