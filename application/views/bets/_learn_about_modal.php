<?php
$CI =& get_instance();
$CI->modal('learn-about-bet-modal', [
    'header' => 'Learn About Bet-a-Friend',
    'body' => '/bets/_learn_about_modal_body',
    'body_string' => false,
    'footer' => '<a href="#" class="btn btn-primary" data-dismiss="modal">OK</a>',
    'modal_size' => 'col-md-6 col-lg-5 col-md-offset-4',
]);