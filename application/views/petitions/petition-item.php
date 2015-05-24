<?php
/** @var Entity\ChangeOrgPetition $petition */
/** @var Base_Controller $CI */
$CI =& get_instance();
?>
<div class="charity-item petition-item block">
    <div class="ribbon-wrapper-red"><div class="ribbon-red">Petition</div></div>

    <h4 class="text-center"><a href="/petitions/<?php echo $petition->getGiverhubUrlSlug(); ?>"><?php echo htmlspecialchars($petition->getTitle()); ?></a></h4>

    <div class="gh_spacer_21">
        <?php
            $progressBar = array();
            $progressBar['goalProgress'] = $petition->getGoalProgress();
            $progressBar['tooltipMessage'] = "This is the petitions progress towards reaching its goal signature count.<br/><br/>" .
                "Signatures: " . $petition->getSignatureCount() . ' ('.Common::formatNumber($petition->getSignatureCount()).')<br/>'.
                'Goal: ' . $petition->getGoal() . ' ('.Common::formatNumber($petition->getGoal()).')';
            $progressBar['areaValueNow'] = $petition->getFormattedSignaturesVsGoal();
            $progressBar['subClasses'] = "gh_popover";
            $progressBar['type'] = 'petition-item';
            $this->view('partials/_progress_bar',$progressBar);
        ?>
    </div>

    <p class="short_description">
        <?php
            $text = explode(' ', strip_tags($petition->getOverview()));
            $text = implode(' ', array_slice($text, 0, 25));
            $description = $text;

            echo $description;
        ?>
    </p>

    <hr/>

    <div class="rating">
        <?php $user = \Base_Controller::$staticUser; ?>
        <?php if ($petition->getStatus() != 'open'): ?>
            <p>This petition is no longer open for signing.</p>
        <?php elseif($petition->hasEnded()): ?>
            <p>This petition has ended.</p>
        <?php elseif($user && $user->hasSignedPetition($petition)): ?>
            <p>You already signed this petition.</p>
            <a
                href="#"
                class="btn btn-danger bold btn-sign-petition-overview unsign gh-trigger-event"
                data-event-category="button"
                data-event-action="click"
                data-event-label="Sign Petition (search result)"
                data-petition-url="<?php echo $petition->getGiverhubUrl(base_url()); ?>"
                data-loading-text="SIGN..."
                data-petition-id="<?php echo $petition->getId(); ?>">UNSIGN</a>
        <?php elseif(!$petition->getAuthKey() && $user): ?>
            <p>This petition can only be signed from change.org. <a target="_blank" href="<?php echo $petition->getUrl(); ?>">Click here</a>.</p>
        <?php else: ?>
            <form action="#" class="gh_donation gh_block_section">
                <div class="row">
                    <div class="col-md-12 gh_spacer_14">
                        <a
                            href="#"
                            class="btn btn-danger bold btn-sign-petition-overview gh-trigger-event"
                            data-event-category="button"
                            data-event-action="click"
                            data-event-label="Sign Petition (search result)"
                            data-petition-url="<?php echo $petition->getGiverhubUrl(base_url()); ?>"
                            data-loading-text="SIGN..."
                            data-petition-id="<?php echo $petition->getId(); ?>">SIGN</a>
                    </div>
                </div>
                <div class="show-on-hover row gh_spacer_7">
                    <div class="col-md-12">
                        <textarea id="sign-petition-reason-overview-<?php echo $petition->getId(); ?>" class="sign-petition-reason-overview form-control" placeholder="Why is this important to you? (Optional)" name="reason"></textarea>
                    </div>
                    <div class="col-md-12">
                        <label for="sign-petition-hidden-overview-<?php echo $petition->getId(); ?>">
                            <input class="sign-petition-hidden-overview"
                                   id="sign-petition-hidden-overview-<?php echo $petition->getId(); ?>"
                                   type="checkbox"
                                   <?php if ($this->user && $this->user->getSignPetitionsAnonymously()): ?>
                                       checked="checked"
                                   <?php endif; ?>
                                   name="hidden"> Hide signature
                        </label>
                        <label for="share-petition-signature-on-facebook-<?php echo $petition->getId(); ?>"><input class="share-petition-signature-on-facebook" id="share-petition-signature-on-facebook-<?php echo $petition->getId(); ?>" type="checkbox" name="share-petition-signature-on-facebook"> Share On Facebook</label>
                    </div>
                </div>
            </form>
        <?php endif; ?>
        <div class="shrink-line-height">This petition is powered by Change.org. By signing, you accept Change.org's <a href="http://www.change.org/about/terms-of-service" target="_blank">terms of service</a> and <a href="http://www.change.org/about/privacy" target="_blank">privacy policy</a>.</div>
    </div>

</div>

