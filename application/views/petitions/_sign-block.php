<?php /** @var \Entity\ChangeOrgPetition $petition */ ?>
<div class="block petition-sign-block">
    <?php if ($petition->getStatus() != 'open'): ?>
        <p>This petition is no longer open for signing.</p>
    <?php elseif($petition->hasEnded()): ?>
        <p>This petition has ended.</p>
    <?php elseif($this->user && $this->user->hasSignedPetition($petition)): ?>
        <p>You already signed this petition.</p>
        <button
            class="pull-right btn btn-danger bold btn-sign-petition-overview unsign gh-trigger-event"
            data-event-category="button"
            data-event-action="click"
            data-event-label="Unsign Petition (from petition overview)">UNSIGN</button>
    <?php elseif(!$petition->getAuthKey() && $this->user): ?>
        <p>This petition can only be signed from change.org. <a target="_blank" href="<?php echo $petition->getUrl(); ?>">Click here</a>.</p>
    <?php else: ?>
        <p class="you-can-now hide">You can now sign the petition</p>
        <form action="#" class="gh_donation">
            <div class="gh_form_block row gh_spacer_21">
                <div class="col-md-12 textarea-wrapper">
                    <textarea id="sign-petition-reason-overview-<?php echo $petition->getId(); ?>" class="sign-petition-reason-overview form-control" rows="3" placeholder="Why is this important to you? (Optional)" name="reason"></textarea>
                </div>
                <div class="col-md-12 buttons-wrapper">
                    <button
                        class="pull-right btn btn-danger bold btn-sign-petition-overview gh-trigger-event"
                        data-event-category="button"
                        data-event-action="click"
                        data-event-label="Sign Petition (from petition overview)"
                        data-petition-url="<?php echo $petition->getGiverhubUrl(base_url()); ?>"
                        data-loading-text="SIGNING..."
                        data-petition-id="<?php echo $petition->getId(); ?>"
                        style="">SIGN</button>

                    <button type="button"
                            class="btn btn-default btn-hide-signature pull-right gh-trigger-event <?php if ($this->user && $this->user->getSignPetitionsAnonymously()): ?>active<?php endif; ?>"
                            data-toggle="button"
                            data-event-category="button"
                            data-event-action="click"
                            data-event-label="Hide Signature? (from petition overview)">
                        <span title="Click here to sign this petition anonymously" class="gh_tooltip">Hide Signature?</span>
                        <span title="Click here to sign this petition publicly" class="active gh_tooltip ">Signature Hidden</span>
                    </button>
                </div>
            </div>
            <!-- clearfix end -->
        </form>
    <?php endif; ?>
    <hr/>
    <span class="change-org-disclaimer">This petition is powered by Change.org. By signing, you accept Change.org's <a href="http://www.change.org/about/terms-of-service" target="_blank">terms of service</a> and <a href="http://www.change.org/about/privacy" target="_blank">privacy policy</a>.</span>
    <!-- gh_donation end -->
</div>
<!-- block end -->
