<?php
/** @var \Entity\ChangeOrgPetition $petition */
/** @var \Petitions $CI */
$CI =& get_instance();
$user = \Base_Controller::$staticUser;
?>

<?php $this->load->view('/petitions/_petition_header', array('petition' => $petition)); ?>

<main class="petition_main change-org-petition-main" role="main" id="main_content_area">
    <section class="container">
        <div class="row">
			<div class="hide-on-sm-md-resolution">
            <?php $this->load->view('/petitions/_petition_new_nav'); ?>
			</div>

            <div class="col-lg-10 col-md-12 col-sm-12">
                <div class="row">
                    <div class="col-md-6">
                        <?php $this->load->view('/petitions/_sign-block', ['petition' => $petition]); ?>

                        <div class="block petition-overview-block">

                            <div id="petition-overview-container" class="blk gh_spacer_7">
                                <div id="petition_show_normal">
                                    <?php
                                    $petitionOverview = Common::truncate($petition->getOverview(),"350"," (...)");
                                    echo $petitionOverview;
                                    ?>
                                </div>
                                <div id="petition_show_more" style="display: none"><?php echo $petition->getOverview(); ?></div>
                            </div>

                            <?php if( strlen($petitionOverview) < strlen($petition->getOverview()) ): ?>
                                <p><a id="expand-petition-overview" href="#">View More</a></p>
                            <?php endif; ?>
                            <a href="<?php echo $petition->getUrl(); ?>">View petition on change.org</a>
                        </div>
                        <!-- block end -->
                    </div>

                    <div class="col-md-6">

                        <?php $this->load->view('/partials/trending-petitions'); ?>

                        <?php // $this->load->view('/members/_nonprofit_feed', ['user' => $user, 'preHtml' => '/petitions/_nonprofit_feed_pre_html', 'extra_classes' => ['petition-page-nonprofit-feed']]); ?>

                        <?php $this->load->view('/petitions/_welcome_video_block'); ?>

                        <div class="block petition-recent-signatures-block">
                            <h3 class="gh_block_title gh_spacer_21">Recent Signatures <i class="icon-updates pull-right"></i></h3>

                            <div class="table-responsive">
                                <?php $this->load->view('/petitions/_petition_recent_signatures_table', array('petition' => $petition)); ?>
                            </div>
                            <!-- table-responsive end -->
                        </div>
                        <!-- block end -->

                    </div>
                </div>
            </div>
        </div>
    <!-- row end -->
    </section>
<!-- container end -->
</main>

<?php $CI->load->view('/partials/_display-photo-modal'); ?>
<?php if ($CI->user) {
    $CI->load->view('partials/_address-modal');
    $CI->load->view('/petitions/_removal_request_modal');
}