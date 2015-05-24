<?php
/** @var \Entity\GivingPot $giving_pot */
?>

<main role="main" class="giving-pot-main" id="giving-pot-edit-main">
    <section class="container">
        <div class="row">
            <div class="col-sm-6">
                <div class="block edit-giving-pot-form-block" data-giving-pot-id="<?php echo $giving_pot->getId(); ?>">
                    <header>Create a Giving Pot <span class="saving hide">Saving...</span></header>
                    <h2>To create a Giving Pot, fill in the fields below</h2>
                    <p>Upload a logo or enter business name</p>
                    <div class="logo-wrapper <?php echo $giving_pot->getCompanyLogo() ? '' : 'hide'; ?>">
                        <img data-empty-src="data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs="
                            src="<?php echo $giving_pot->getCompanyLogo() ?
                                $giving_pot->getCompanyLogo() :
                                'data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs='; ?>">
                        <button type="button"
                                data-giving-pot-id="<?php echo $giving_pot->getId(); ?>"
                                class="btn btn-danger btn-xs btn-delete-giving-pot-logo">Remove Logo</button>
                    </div>
                    <div class="logo-or-name-wrapper <?php echo $giving_pot->getCompanyLogo() ? 'hide' : ''; ?>">
                        <form id="upload-giving-pot-logo-form" action="/upload/giving_pot_logo" method="POST" enctype="multipart/form-data">
                            <button class="btn btn-primary" type="button">Upload Logo</button>
                            <input type="file" name="logo-input" accept="image/*">
                            <input type="hidden" name="giving-pot-id" value="<?php echo $giving_pot->getId(); ?>">
                        </form>
                        <span> or </span>
                        <input class="form-control trigger-render input-company-name"
                               placeholder="Enter company name"
                               type="text"
                               value="<?php echo htmlspecialchars($giving_pot->getCompanyName()); ?>">
                    </div>
                    <div class="logo-or-name-error alert alert-danger hide" role="alert">
                        <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                        <span class="sr-only">Error:</span>
                        Enter a name or upload a logo
                    </div>

                    <label class="pot-size-label" for="edit-pot-size-id">
                        Pot Size (The total amount of funds that will be awarded)
                    </label>
                    <input id="edit-pot-size-id"
                           class="form-control input-pot-size trigger-render"
                           placeholder="$"
                           value="<?php echo $giving_pot->getPotSize(); ?>">
                    <div class="pot-size-error alert alert-danger hide" role="alert">
                        <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                        <span class="sr-only">Error:</span>
                        The Pot Size must be a number over 10. Do not write a dollar sign. Only digits!
                    </div>

                    <label class="summary-label" for="edit-summary-id">
                        Summary (e.g. $10 for every $100 spent)
                    </label>
                    <input id="edit-summary-id"
                           class="form-control input-summary trigger-render"
                           placeholder="<140 characters"
                           maxlength="140"
                           value="<?php echo htmlspecialchars($giving_pot->getSummary()); ?>">
                    <div class="summary-error alert alert-danger hide" role="alert">
                        <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                        <span class="sr-only">Error:</span>
                        You need to write a summary. (Max 140 chars)
                    </div>

                    <label class="body-label" for="edit-body-id">
                        Body (A more detailed description of the terms of the Giving Pot)
                    </label>
                    <textarea id="edit-body-id"
                           class="form-control input-body trigger-render"><?php echo htmlspecialchars($giving_pot->getBody()); ?></textarea>
                    <div class="body-error error-wide alert alert-danger hide" role="alert">
                        <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                        <span class="sr-only">Error:</span>
                        You need to write a body.
                    </div>

                    <label class="button-text-label" for="edit-button-text-id">
                        Button Text (The text you would like displayed inside the button, e.g. Start Donating)
                    </label>
                    <input id="edit-button-text-id"
                           class="form-control input-button-text trigger-render"
                           placeholder="<140 characters"
                           maxlength="140"
                           value="<?php echo htmlspecialchars($giving_pot->getButtonText()); ?>">
                    <div class="button-text-error alert alert-danger hide" role="alert">
                        <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                        <span class="sr-only">Error:</span>
                        You need to enter a button text. (Max 140 chars)
                    </div>

                    <label class="button-url-label" for="edit-button-url-id">
                        Button Link (the URL you would like the button to direct to)
                    </label>
                    <input id="edit-button-url-id"
                           class="form-control input-button-url trigger-render"
                           placeholder="http://"
                           value="<?php echo htmlspecialchars($giving_pot->getButtonUrl()); ?>">
                    <div class="button-url-error error-wide alert alert-danger hide" role="alert">
                        <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                        <span class="sr-only">Error:</span>
                        You need to enter a button url. It needs to start with <span class="bold">http://</span> or <span class="bold">https://</span>
                    </div>

                    <div class="payment-method">
                        <?php $this->load->view('/giving-pot/_payment-method', ['giving_pot' => $giving_pot]); ?>
                    </div>

                    <button type="button"
                            data-loading-text="Publish"
                            class="btn btn-primary btn-publish-giving-pot">Publish</button>
                </div>
            </div>
            <div class="col-sm-6 giving-pot-preview-wrapper">
                <?php echo $giving_pot->render(); ?>
            </div>
        </div>
    </section>
</main>
<?php
$this->load->view('/partials/_donation_modals');