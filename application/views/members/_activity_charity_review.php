<?php /** @var \Base_Controller $this */ ?>
<?php /** @var string $context */ ?>
<?php /** @var \Entity\CharityReview $charityReview */ ?>
<tr class="activity-charity-review-tr hide-activity two-col" data-activity-id="<?php echo get_class($charityReview) . $charityReview->getId(); ?>">
    <td class="activity">
        <span class="icon"><i class="icon-star"></i></span>
        <?php echo $this->user && $charityReview->getUserId() == $this->user->getId() ? 'You' : 'Your friend <a href="/member/' .$charityReview->getUser()->getUsername() .'">'. htmlspecialchars($charityReview->getUser()->getName()) . '</a>'; ?> reviewed the Charity <a href="<?php echo $charityReview->getCharity()->getUrl(); ?>"><?php echo htmlspecialchars($charityReview->getCharity()->getName()); ?></a>
        <?php $this->load->view('/members/_activity_feed_post_comments_container', ['entity' => $charityReview, 'context' => $context, 'extra_button' => '']); ?>
    </td>
    <td class="activity_cf_std">
        <div class="date-div"><?php echo $charityReview->getDateTime()->format('m/d/Y'); ?><br/><?php echo $charityReview->getDateTime()->format('h:i A'); ?></div>
        <?php if ($context == 'my'): ?>
            <?php $this->load->view('/members/_activity_hide', ['activity_id' => $charityReview->getId(), 'activity_type' => 'charity-review']); ?>
        <?php endif; ?>
    </td>
</tr>
