<?php
/** @var \Base_Controller $this */
/** @var \Entity\Challenge $challenge */
/** @var string $context */
$CI =& get_instance();
?>
<tr class="activity-challenge-tr" data-activity-id="<?php echo get_class($challenge) . $challenge->getId(); ?>">
    <td class="challenge hide-activity" colspan="2">
        <?php if ($context == 'my'): ?>
            <?php $this->load->view('/members/_activity_hide', ['activity_id' => $challenge->getId(), 'activity_type' => 'challenge']); ?>
        <?php endif; ?>
        <?php $CI->load->view('/challenges/_info.php', ['challenge' => $challenge, 'context' => $context]); ?>

        <?php $extra_button = '<a href="#" data-challenge-url="'.$challenge->getUrl().'" class="btn-share-challenge-on-facebook">Share</a>'; ?>
        <?php $CI->load->view('/members/_activity_feed_post_comments_container', ['entity' => $challenge, 'context' => $context, 'extra_button' => $extra_button]); ?>
    </td>
</tr>
