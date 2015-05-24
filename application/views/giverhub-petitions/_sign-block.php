<h3 class="gh_block_title gh_spacer_21">Sign Petition</h3>
<?php if ($petition->getStatus() != 'open'): ?>
    <p>This petition is no longer open for signing.</p>
<?php elseif ($this->user && $this->user->hasSignedGiverhubPetition($petition)): ?>
    <p>You already signed this petition.</p>
    <button
        class="pull-right btn btn-danger bold btn-sign-g-petition-overview unsign gh-trigger-event"
        data-event-category="button"
        data-event-action="click"
        data-petition-id="<?php echo $petition->getId(); ?>"
        data-loading-text="UNSIGN"
        data-event-label="Unsign Petition (from petition overview)">UNSIGN</button>
<?php else: ?>
    <form action="#" class="gh_donation">
        <div class="gh_form_block row gh_spacer_21 petition-sign-block">

            <div class="col-md-12 gh_spacer_7">
                <textarea class="sign-petition-reason-overview form-control" rows="3" placeholder="Why is this important to you? (Optional)" name="reason"></textarea>
            </div>
            <div class="col-md-12 sign-petition-right-block g-petition-sign-block buttons-wrapper">
                <button
                    class="pull-right btn btn-danger bold btn-sign-g-petition-overview gh-trigger-event"
                    data-event-category="button"
                    data-event-action="click"
                    data-event-label="Sign Petition (from petition overview)"
                    data-petition-url="<?php echo $petition->getFullUrl(); ?>"
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