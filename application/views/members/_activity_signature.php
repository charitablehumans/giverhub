<?php
/** @var \Base_Controller $this */
/** @var \Entity\UserPetitionSignature|\Entity\PetitionSignature $signature*/
/** @var string $context */
?>
<tr class="activity-user-follower-tr hide-activity two-col" data-activity-id="<?php echo get_class($signature) . $signature->getId(); ?>">
    <td class="petition-activity" colspan="2">
        <div class="top-wrapper">
            <div class="content-div">
                <?php if ($signature->isHidden()): ?>
                    Someone
                <?php else: ?>
                    <?php echo $signature->getUser()->getLink(); ?>
                <?php endif; ?>

                signed the petition <?php echo $signature->getPetition()->getLink(); ?>
            </div>
            <div class="date_div">
                <?php echo $signature->getSignedAtDt()->format('m/d/Y'); ?><br/><?php echo $signature->getSignedAtDt()->format('h:i A'); ?>
            </div>
            <?php if ($context == 'my'): ?>
                <?php $this->load->view('/members/_activity_hide', ['activity_id' => $signature->getId(), 'activity_type' => $signature instanceof \Entity\UserPetitionSignature ? 'co-pet-signature' : 'gh-pet-signature']); ?>
            <?php endif; ?>
        </div>
        <div class="petition-wrapper">
            <?php $petition = $signature->getPetition(); ?>
            <?php if ($petition->hasMedia()): ?>
                <?php echo $petition->getMediaHtml(); ?>
            <?php endif; ?>
            <div class="summary dotdotdot trigger-ellipsis <?php echo $petition->hasMedia() ? 'has-img' : ''; ?>"><?php echo $petition->getOverview(); ?></div>
        </div>
        <?php $this->load->view('/members/_activity_feed_post_comments_container', ['entity' => $signature, 'context' => $context, 'extra_button' => '']); ?>
    </td>
</tr>
