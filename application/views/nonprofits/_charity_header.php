<?php
/** @var \Entity\Charity $charity */
/** @var \Charity $CI */
$CI =& get_instance();
?>
<?php $GLOBALS['super_timers']['ch1'] = microtime(true) - $GLOBALS['super_start']; ?>
<section class="gh_secondary_header nonprofit-secondary-header clearfix <?php if ($charity->hasCover()): ?>nonprofit-has-cover<?php endif; ?>"
         <?php if ($charity->hasCover()): ?>style="background-repeat: no-repeat; background-image: url(<?php echo $charity->getCover() . '?'.crc32(rand()); ?>);"<?php endif; ?>>

    <section class="container org_header">
        <div class="row">
            <div class="col-sm-2 col-md-2 upload-logo-wrapper <?php if(!$charity->hasLogo()): ?>hide upload-logo-wrapper-hidden<?php endif; ?>">
                <img class="nonprofit-logo-img <?php if(!$charity->hasLogo()): ?>hide<?php endif; ?>" src="<?php echo $charity->getLogo() ? $charity->getLogo() : '#'; ?>" alt="<?php echo htmlspecialchars($charity->getName()); ?>">
                <?php if ($this->user && $this->user->isCharityAdmin($charity)): ?>
                    <span class="your-logo-here <?php if($charity->hasLogo()): ?>hide<?php endif; ?>">Your Logo Here</span>
                    <form id="upload-charity-logo" action="/upload/charity_logo" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="charity_id" value="<?php echo $charity->getId(); ?>">
                        <a class="fileinput-button btn btn-sm btn-success" type="button">
                            <span>Change Logo</span><input type="file" name="charity-logo" accept="image/*">
                        </a>
                        <button
                            data-charity-id="<?php echo $charity->getId(); ?>"
                            data-loading-text="Remove"
                            class="<?php if (!$charity->hasLogo()): ?>hide<?php endif; ?> btn btn-danger btn-sm btn-delete-charity-logo">Remove</button>
                    </form>

                <?php endif; ?>
            </div>
            <div class="<?php if($charity->hasLogo()): ?>col-sm-6 col-md-7<?php else: ?>col-sm-8 col-md-9<?php endif; ?> nonprofit-name-wrapper">
                <div class="row">
                    <div class="col-sm-6">
                        <h1 class="org_name">
                            <?php echo htmlspecialchars($charity->getName()); ?>
                            <?php if ($CI->user && $CI->user->isCharityAdmin($charity)): ?>
                                <button type="button"
                                        class="btn btn-success btn-edit-nonprofit-name">
                                    <i class="glyphicon glyphicon-edit"></i> Edit
                                </button>
                                <?php $CI->modal('nonprofit-admin-edit-name-url-modal', [
                                    'header' => 'Edit',
                                    'modal_size' => 'col-md-6 col-lg-5',
                                    'body' => '/modals/nonprofit-admin-edit-name-url-modal-body',
                                    'body_string' => false,
                                    'body_data' => ['charity' => $charity],
                                ]); ?>
                            <?php endif; ?>
                        </h1>
                        <span class="org_slogan"><?php echo htmlspecialchars($charity->getMissionSummary()); ?></span>
                        <?php if ($CI->user && $CI->user->isAdmin()): ?>
                            <?php $CI->load->view('/nonprofits/_citizen_button', ['charity' => $charity]); ?>
                        <?php endif; ?>
                    </div>
                    <div class="col-sm-6 keywords hidden-xs">
                        <div class="labels pull-right">
                            <ul id="charity-keywords-container">
                                <?php $CI->load->view('nonprofits/_charity_header_keywords', array('charity' => $charity)); ?>
                            </ul>
                            <?php if($CI->user): ?>
                                <a href="#" class="btn-add-keyword pull-right"><i class="icon-caret-left"></i> ADD KEYWORD</a>
                            <?php else: ?>
                                <a data-toggle="modal" href="#signin-or-join-first-modal" class="pull-right"><i class="icon-caret-left"></i> ADD KEYWORD</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <?php if ($charity->getOverallScore() !== null): ?>
                    <div class="progress">
                        <div class="progress-bar progress-bar-success gh_popover" role="progressbar" aria-valuenow="<?php echo round($charity->getOverallScore()); ?>"
                             aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $charity->getOverallScore(); ?>%"
                             data-trigger="hover" data-placement="bottom" data-toggle="popover" data-container="body"
                             data-content="<?php echo htmlspecialchars(\Entity\Charity::$overallScoreText); ?>">
                            <span class=""><?php echo round($charity->getOverallScore()); ?> / 100</span>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <?php $GLOBALS['super_timers']['ch3'] = microtime(true) - $GLOBALS['super_start']; ?>

            <div class="col-sm-4 col-md-3 clear-md charity_header_donate">
                <address>
                    <?php if ($charity->getIrsStreetAddress() != 'N/A'): ?>
                        <?php echo htmlspecialchars($charity->getIrsStreetAddress()); ?><br/>
                    <?php endif; ?>

                    <?php if ($charity->getIrsZipcode() != 'N/A'): ?>
                        <?php echo htmlspecialchars($charity->getIrsZipcode()); ?>
                    <?php endif; ?>

                    <?php if ($charity->getIrsCity() != 'N/A'): ?>
                        <?php echo htmlspecialchars($charity->getIrsCity()); ?>,<br/>
                    <?php elseif ($charity->getCityName()): ?>
                        <?php echo htmlspecialchars($charity->getCityName()); ?>,<br/>
                    <?php endif; ?>

                    <?php if ($charity->getIrsState() != 'N/A'): ?>
                        <?php echo htmlspecialchars($charity->getIrsStateFullName()); ?><br/>
                    <?php else: ?>
                        <?php echo htmlspecialchars($charity->getStateFullName()); ?><br/>
                    <?php endif; ?>

                    <?php if (!$charity->hasFakeEin()): ?>
                        EIN: <?php echo $charity->getEin(true); ?>
                    <?php endif; ?>
                </address>

                <?php if ($CI->user && $CI->user->isCharityAdmin($charity)): ?>
                    <div class="charity-admin-container col-md-12">
                        You are an administrator of this nonprofit!<br/>
                        <button class="btn btn-primary btn-sm btn-edit-charity-admin-facebook-page" type="button">Add Facebook Page</button>

                        <form id="upload-charity-header-pic" action="/upload/charity_header_pic" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="charity_id" value="<?php echo $charity->getId(); ?>">
                            <a class="fileinput-button btn btn-sm btn-success" type="button">
                                <span>Change Cover</span><input type="file" name="charity-header-pic" accept="image/*">
                            </a>
                        </form>

                        <button
                            data-charity-id="<?php echo $charity->getId(); ?>"
                            data-loading-text="Remove Cover"
                            class="<?php if (!$charity->hasCover()): ?>hide<?php endif; ?> btn btn-danger btn-sm btn-delete-charity-header-pic">Remove Cover</button>

                        <button type="button"
                                class="btn btn-sm btn-danger btn-start-upload-nonprofit-logo">Upload Logo</button>

                    </div>
                <?php else: ?>
                    <div id="request-charity-admin-container" class="charity-admin-container col-md-12 <?php if ($CI->user && $CI->user->getCharityAdminRequest($charity)): ?>requested<?php endif; ?>">
                        <a href="#" data-toggle="modal" data-target="#<?php if ($CI->user): ?>request-charity-admin-modal<?php else: ?>signin-or-join-first-modal<?php endif; ?>">Become an admin for this nonprofit</a>
                        <span>We are processing your request.</span>
                    </div>
                <?php endif; ?>
            </div>
            <?php $GLOBALS['super_timers']['ch6'] = microtime(true) - $GLOBALS['super_start']; ?>
        </div>
        <!-- row end -->
    </section>
    <!-- org_header end -->
</section>
<script type="application/ld+json">
<?php echo $charity->getLdJsonSchema(); ?>
</script>

<?php
$CI->modal('add-keyword-modal', [
    'header' => 'Keywords',
    'modal_size' => 'col-md-4',
    'body' => '<input type="text" class="form-control" id="add-keyword-keyword" placeholder="Keyword">
                <span class="help-block">Use commas to separate keywords, e.g. art, opera</span>',
    'body_string' => true,
    'footer' => '<a data-charity-id="'.$charity->getId().'" href="#" class="btn btn-primary btn-add-keyword-save" data-loading-text="SAVING...">SAVE</a>
                <a href="#" data-dismiss="modal" class="btn">CANCEL</a>',
]);
$CI->modal('request-charity-admin-modal', [
    'header' => 'Request Admin',
    'modal_size' => 'col-md-4',
    'body' => '/modals/request-charity-admin-modal-body',
    'body_string' => false,
    'body_data' => ['charity' => $charity],
    'footer' => '<a id="submit-request-charity-admin-form" data-charity-id="'.$charity->getId().'" href="#" class="btn btn-primary" data-loading-text="SUBMIT">SUBMIT</a>',
]);

if ($CI->user) {
    $CI->load->view('partials/_donation_modals');
}
if ($CI->user && $CI->user->isCharityAdmin($charity)) {
    $CI->load->view('partials/_charity-admin-modal', ['charity' => $charity]);
}
$GLOBALS['super_timers']['ch7'] = microtime(true) - $GLOBALS['super_start'];