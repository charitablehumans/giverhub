<?php
/** @var \Entity\ChangeOrgPetition $petition */
/** @var \Petitions $CI */
$CI =& get_instance();
$user = \Base_Controller::$staticUser;
?>

<section class="gh_secondary_header clearfix petition_page_gh_secondary_header"></section>

<main class="petition_main change-org-petition-main" role="main" id="main_content_area">

    <section class="container">
        <div class="row">
            <h2 class="center">This petition is no longer available on GiverHub</h2>
        </div>
        <div class="row">
            <div class="col-md-6">
                <?php $this->load->view('/members/_nonprofit_feed', ['user' => $user, 'preHtml' => '/petitions/_nonprofit_feed_pre_html', 'extra_classes' => ['petition-page-nonprofit-feed']]); ?>
                <?php $this->load->view('/members/_petition_feed', ['user' => $user]); ?>
            </div>

            <div class="col-md-6">
                <?php $this->load->view('/petitions/_welcome_video_block'); ?>
                <?php $this->load->view('/partials/_fun_donations'); ?>
            </div>
        </div>
    </section>
</main>