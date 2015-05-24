<?php
    /** @var \Base_Controller $CI */
    $CI =& get_instance();
?>
<?php if (!$CI->user): ?>
    <?php $CI->modal('introduction-video-modal', [
        'header' => 'Welcome',
        'modal_size' => 'col-lg-6 col-md-6 col-sm-10',
        'body' => '<div id="introduction-video-div" class="youtube-player youtube-iframe nonprofits-page-intro"></div>',
        'body_string' => true,
    ]); ?>
<?php endif; ?>