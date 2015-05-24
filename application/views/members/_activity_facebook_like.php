<?php
/** @var \Base_Controller $this */
/** @var string $context */
/** @var \Entity\FacebookLike $facebookLike */
?>
<tr class="activity-facebook-like-tr hide-activity two-col" data-activity-id="<?php echo get_class($facebookLike) . $facebookLike->getId(); ?>">
    <td class="activity"><span class="icon"><i class="<?php if ($this->user && $facebookLike->getUserId() == $this->user->getId()): ?>icon-user-following<?php else: ?>icon-follower<?php endif; ?>"></i></span>
        <?php if ($this->user && $facebookLike->getUserId() == $this->user->getId()): ?>
            You
        <?php else: ?>
            <a href="/member/<?php echo $facebookLike->getUser()->getUsername(); ?>"><?php echo htmlspecialchars($facebookLike->getUser()->getName()); ?></a>
        <?php endif; ?>
        Liked <a href="<?php echo $facebookLike->getCharity()->getUrl(); ?>"><?php echo htmlspecialchars($facebookLike->getCharity()->getName()); ?></a>
        <?php $this->load->view('/members/_activity_feed_post_comments_container', ['entity' => $facebookLike, 'context' => $context, 'extra_button' => '']); ?>
    </td>
    <td class="activity_cf_std">
        <div class="date-div"><?php echo $facebookLike->getLikedAtDt()->format('m/d/Y'); ?><br/><?php echo $facebookLike->getLikedAtDt()->format('h:i A'); ?></div>
        <?php if ($context == 'my'): ?>
            <?php $this->load->view('/members/_activity_hide', ['activity_id' => $facebookLike->getId(), 'activity_type' => 'facebook-like']); ?>
        <?php endif; ?>
    </td>
</tr>