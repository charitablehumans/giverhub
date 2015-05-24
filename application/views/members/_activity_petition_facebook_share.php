<?php
/** @var \Base_Controller $this */
/** @var \Entity\ChangeOrgPetitionFacebookShare|\Entity\PetitionFacebookShare $facebook_share */
/** @var string $context */
?>
<tr class="activity-user-follower-tr hide-activity two-col" data-activity-id="<?php echo get_class($facebook_share) . $facebook_share->getId(); ?>">
    <td class="petition-activity" colspan="2">
        <div class="top-wrapper">
            <div class="content-div">
                <?php echo $facebook_share->getUser()->getLink(); ?>
                shared the petition <?php echo $facebook_share->getPetition()->getLink(); ?> on facebook
            </div>
            <div class="date_div">
                <?php echo $facebook_share->getDate()->format('m/d/Y'); ?><br/><?php echo $facebook_share->getDate()->format('h:i A'); ?>
            </div>
            <?php if ($context == 'my'): ?>
                <?php $this->load->view('/members/_activity_hide', ['activity_id' => $facebook_share->getId(), 'activity_type' => $facebook_share instanceof \Entity\ChangeOrgPetitionFacebookShare ? 'co-pet-facebook-share' : 'gh-pet-facebook-share']); ?>
            <?php endif; ?>
        </div>
        <div class="petition-wrapper">
            <?php $petition = $facebook_share->getPetition(); ?>
            <?php if ($petition->hasMedia()): ?>
                <?php echo $petition->getMediaHtml(); ?>
            <?php endif; ?>
            <div class="summary dotdotdot trigger-ellipsis <?php echo $petition->hasMedia() ? 'has-img' : ''; ?>"><?php echo $petition->getOverview(); ?></div>
        </div>
        <?php $this->load->view('/members/_activity_feed_post_comments_container', ['entity' => $facebook_share, 'context' => $context, 'extra_button' => '']); ?>
    </td>
</tr>