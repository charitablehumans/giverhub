<?php

    /** @var \Base_Controller $CI */
    $CI =& get_instance();

    if ($CI->bet_modals_included_already) {
        return;
    }
    $CI->bet_modals_included_already = true;

    /** @var \Entity\User $user */
?>

<?php $CI->load->view('bets/_learn_about_modal'); ?>

<?php /* NOTE: user may be null ,, because anon users can look at a post .. for example giverhub.com/post/ds43r34e43t34ds .. but the "learn" modal needs to be included anyway */ ?>
<?php if ($CI->user): ?>

    <?php $CI->modal('bet-modal', [
        'modal_size' => 'col-md-5 col-lg-4 col-md-offset-4',
        'header' => 'Bet-a-Friend',
        'body' => '/bets/_bet_form',
        'body_string' => false,
        'footer' => '<a href="#" class="pull-left btn-make-bet-save-for-later">Save this bet for later</a> <a href="#" class="btn btn-warning btn-make-bet-review">REVIEW</a>',
    ]); ?>

    <?php $CI->modal('confirm-bet-modal', [
        'extra_classes' => 'centered-bet-modal',
        'modal_size' => 'col-md-5 col-lg-4 col-md-offset-4',
        'header' => 'Confirm Bet',
        'body' => '/bets/_confirm-bet-modal-body',
        'body_string' => false,
    ]); ?>

    <?php $CI->modal('view-pending-bet-modal', [
        'extra_classes' => 'centered-bet-modal',
        'modal_size' => 'col-md-5 col-lg-4 col-md-offset-4',
        'header' => 'View Pending Bet',
        'body' => '/bets/_view-pending-bet-modal-body',
        'body_string' => false,
    ]); ?>

    <?php $CI->modal('accept-prompt-bet-modal', [
        'extra_classes' => 'centered-bet-modal weight-normal',
        'modal_size' => 'col-md-5 col-lg-4 col-md-offset-4',
        'header' => 'Accept Bet',
        'body' => '/bets/_accept-prompt-bet-modal-body',
        'body_string' => false,
    ]); ?>

    <?php $CI->modal('bet-success-choose-charity-modal', [
        'extra_classes' => 'centered-bet-modal',
        'modal_size' => 'col-md-5 col-lg-4 col-md-offset-4',
        'header' => 'Pick Nonprofit',
        'body' => '/bets/_bet-success-choose-charity-modal-body',
        'body_string' => false,
    ]); ?>

    <?php $CI->modal('view-bet-modal', [
        'extra_classes' => 'centered-bet-modal',
        'modal_size' => 'col-md-5 col-lg-4 col-md-offset-4',
        'header' => 'View Bet',
        'body' => '',
        'body_string' => true,
    ]); ?>
<?php endif; ?>