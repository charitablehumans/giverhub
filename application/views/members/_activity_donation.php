<?php /** @var \Base_Controller $this */ ?>
<?php /** @var string $context */ ?>
<?php /** @var \Entity\Donation $donation */ ?>
<tr class="activity-donation-tr hide-activity two-col" data-activity-id="<?php echo get_class($donation) . $donation->getId(); ?>">
    <td class="activity activity-donation-td">
        <span class="icon"><i class="icon-heart"></i></span>
        <?php if ($donation->getHidden()): ?>
            A donation was made to <?php echo $donation->getCharity()->getLink(); ?>
        <?php else: ?>
            <?php echo $donation->getUser()->getLink(); ?> donated $<?php echo $donation->getAmount(); ?> to <?php echo $donation->getCharity()->getLink(); ?>
        <?php endif; ?>
        <?php $this->load->view('/members/_activity_feed_post_comments_container', ['entity' => $donation, 'context' => $context, 'extra_button' => '']); ?>
    </td>
    <td class="activity_cf_std activity-donation-date-td">
        <div class="date-div">
            <?php echo $donation->getDateTime()->format('m/d/Y'); ?><br/><?php echo $donation->getDateTime()->format('h:i A'); ?>
        </div>
        <?php if ($context == 'my'): ?>
            <?php $this->load->view('/members/_activity_hide', ['activity_id' => $donation->getId(), 'activity_type' => 'donation']); ?>
        <?php endif; ?>
    </td>
</tr>