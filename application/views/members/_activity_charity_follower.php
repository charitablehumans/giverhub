<?php /** @var \Base_Controller $this */ ?>
<?php /** @var string $context */ ?>
<?php /** @var \Entity\CharityFollower $charityFollower */ ?>
<tr class="activity-charity-follower-tr hide-activity two-col"
    data-activity-id="<?php echo get_class($charityFollower) . $charityFollower->getId(); ?>">
    <td class="activity">
        <span class="icon"><i class="icon-star"></i></span> <?php echo $this->user && $charityFollower->getUserId() == $this->user->getId() ? 'You' : 'Your friend <a href="/member/'.$charityFollower->getUser()->getUsername(). '">' . htmlspecialchars($charityFollower->getUser()->getName()) . '</a>'; ?> started following Charity <a href="<?php echo $charityFollower->getCharity()->getUrl(); ?>"><?php echo htmlspecialchars($charityFollower->getCharity()->getName()); ?></a>
        <?php $this->load->view('/members/_activity_feed_post_comments_container', ['entity' => $charityFollower, 'context' => $context, 'extra_button' => '']); ?>
    </td>
    <td class="activity_cf_std">
        <div class="date-div"><?php echo $charityFollower->getDateTime()->format('m/d/Y'); ?><br/><?php echo $charityFollower->getDateTime()->format('h:i A'); ?></div>
        <?php if ($context == 'my'): ?>
            <?php $this->load->view('/members/_activity_hide', ['activity_id' => $charityFollower->getId(), 'activity_type' => 'charity-follower']); ?>
        <?php endif; ?>
    </td>
</tr>
