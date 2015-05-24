<?php /** @var \Base_Controller $this */ ?>
<?php /** @var string $context */ ?>
<?php /** @var \Entity\CharityChangeHistory $charity_change_history */ ?>
<?php
    $action = $charity_change_history->getOldValue() ? 'edited the' : 'added a';
?>
<tr class="activity-charity-change-tr hide-activity two-col" data-activity-id="<?php echo get_class($charity_change_history) . $charity_change_history->getId(); ?>">
    <td class="activity">
        <span class="icon"><i class="icon-star"></i></span> <?php echo $this->user && $charity_change_history->getUserId() == $this->user->getId() ? 'You' : $charity_change_history->getUser()->getLink(); ?> <?php echo $action; ?> <?php echo htmlspecialchars($charity_change_history->getField()); ?> for <a href="<?php echo $charity_change_history->getCharity()->getUrl(); ?>"><?php echo htmlspecialchars($charity_change_history->getCharity()->getName()); ?></a>
        <?php $this->load->view('/members/_activity_feed_post_comments_container', ['entity' => $charity_change_history, 'context' => $context, 'extra_button' => '']); ?>
    </td>
    <td class="activity_cf_std">
        <div class="date-div">
            <?php echo $charity_change_history->getDateTimeDt()->format('m/d/Y'); ?><br/><?php echo $charity_change_history->getDateTimeDt()->format('h:i A'); ?>
        </div>
        <?php if ($context == 'my'): ?>
            <?php $this->load->view('/members/_activity_hide', ['activity_id' => $charity_change_history->getId(), 'activity_type' => 'charity-change']); ?>
        <?php endif; ?>
    </td>
</tr>
