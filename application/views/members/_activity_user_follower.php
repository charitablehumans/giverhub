<?php
/** @var \Base_Controller $this */
/** @var \Entity\UserFollower $userFollower */
/** @var string $context */
?>
<tr class="activity-user-follower-tr hide-activity two-col" data-activity-id="<?php echo get_class($userFollower) . $userFollower->getId(); ?>">
    <td class="activity"><span class="icon"><i class="<?php if ($this->user && $userFollower->getFollowerUserId() == $this->user->getId()): ?>icon-user-following<?php else: ?>icon-follower<?php endif; ?>"></i></span>
        <?php if ($this->user && $userFollower->getFollowerUserId() == $this->user->getId()): ?>
            You
        <?php else: ?>
            <a href="/member/<?php echo $userFollower->getFollowerUser()->getUsername(); ?>"><?php echo htmlspecialchars($userFollower->getFollowerUser()->getName()); ?></a>
        <?php endif; ?>
        Started following
        <?php if ($this->user && $userFollower->getFollowedUserId() == $this->user->getId()): ?>
            you
        <?php else: ?>
            <a href="/member/<?php echo $userFollower->getFollowedUser()->getUsername(); ?>"><?php echo htmlspecialchars($userFollower->getFollowedUser()->getName()); ?></a>
        <?php endif; ?>
        <?php $this->load->view('/members/_activity_feed_post_comments_container', ['entity' => $userFollower, 'context' => $context, 'extra_button' => '']); ?>
    </td>
    <td class="activity_cf_std">
        <div class="date-div"><?php echo $userFollower->getDateTime()->format('m/d/Y'); ?><br/><?php echo $userFollower->getDateTime()->format('h:i A'); ?></div>
        <?php if ($context == 'my'): ?>
            <?php $this->load->view('/members/_activity_hide', ['activity_id' => $userFollower->getId(), 'activity_type' => 'user-follower']); ?>
        <?php endif; ?>
    </td>
</tr>