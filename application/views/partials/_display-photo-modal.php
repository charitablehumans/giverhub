<?php
/** @var \Base_Controller $CI */
$CI =& get_instance();
$CI->modal('display-photo-modal', [
    'header' => 'Picture',
    'modal_size' => 'col-md-10',
    'body' => '<img id="display-photo-modal-img" alt="Display Photo" src="" class="img-responsive"/>',
    'body_string' => true,
    'footer' => '<button type="button" class="btn btn-primary pull-right" data-dismiss="modal">OK</button>',
    'footer_string' => true,
]);