<?php
/** @var \Entity\ChangeOrgPetition $petition */
/** @var \Petitions $CI */
$CI =& get_instance();
?>
<section class="gh_secondary_header clearfix petition_page_gh_secondary_header">

    <section class="container org_header petition-header">
        <div class="row petition-header-row">

            <div class="col-md-5">
                <div class="petition-header-sub-container spacing-xs-resolutions">
                    <h1 class="org_name petition-title petition-title-dotdotdot"><?php echo htmlspecialchars($petition->getTitle()); ?></h1>
                    <?php $targets = $petition->getTargets(); ?>
                    <?php if ($targets): ?>
                        <?php $first_target = $targets[0]; ?>
                        <div class="org_slogan petition-targets gh_tooltip" title="<?php echo htmlspecialchars($petition->getTargetsString()); ?>" data-placement="bottom">Petitioning <?php echo htmlspecialchars($first_target->getName()); ?></div>
                    <?php endif; ?>


                    <div class="org_slogan petition-by">
                        <?php if ($petition->getCreatorName()): ?>
                            Petition by
                            <?php if ($petition->getCreatorUrl()): ?>
                                <a href="<?php echo $petition->getCreatorUrl(); ?>" target="_blank"><?php echo htmlspecialchars($petition->getCreatorName()); ?></a>
                            <?php else: ?>
                                <?php echo htmlspecialchars($petition->getCreatorName()); ?>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-xs-6 col-sm-4 petition-image-wrapper">
                <?php if ($petition->getImageUrl()): ?><img class="petition-image hide-on-medium-resolution" src="<?php echo $petition->getImageUrl(); ?>" alt="<?php echo htmlspecialchars($petition->getTitle()); ?>"><?php endif; ?>
            </div>


            <div class="col-md-4 col-xs-6 col-sm-8">
                <address>
                    <?php if ($petition->hasGoal()): ?>
                        <div class="row">
                            <div class="col-md-5">Signatures: </div>
                            <div class="col-md-7">
                                <div class="progress progress-secondary petition-progress">
                                    <div
                                        class="progress-bar progress-bar-danger noise"
                                        role="progressbar"
                                        aria-valuenow="<?php echo $petition->getGoalProgress(); ?>"
                                        aria-valuemin="0"
                                        aria-valuemax="100"
                                        style="width:<?php echo $petition->getGoalProgress(); ?>%"><?php echo $petition->getSignatureCount() . ' / ' . $petition->getGoal(); ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-7 col-md-offset-5">
                                <p class="signatures-left"><?php echo $petition->getGoal() - $petition->getSignatureCount(); ?> left</p>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="row">
                            <div class="col-md-6 col-md-offset-6">
                                <p>Signatures : <?php echo $petition->getSignatureCount(); ?></p>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="row">
                        <div class="col-md-5">
                            <?php if ($petition->hasGoal()): ?>
                                <p class="pull-left">Goal : <?php echo $petition->getGoal(); ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-7">
                            <p>Created : <?php echo $petition->getCreatedAtDt()->format('m/d/Y'); ?></p>
                            <?php if ($petition->hasEnd()): ?>
                                <p>Ends : <?php echo $petition->getEndAtDt()->format('m/d/Y'); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </address>
                <div class="col-md-6">

                </div>

                <div class="col-md-6">

                </div>
            </div>

        </div>
        <!-- row end -->
    </section>
</section><!-- gh_secondary_header end -->
<?php
if ($this->user) {
    $this->load->view('/partials/_donation_modals');
}