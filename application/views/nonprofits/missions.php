<?php
/** @var \Entity\Charity $charity */
/** @var \Entity\Mission|null $my_mission */
/** @var \Charity $CI */
$CI =& get_instance();

$missions = $charity->getMissions();
$mission_count = count($missions);
?>

<?php $this->load->view('/nonprofits/_charity_header', array('charity' => $charity)); ?>

<main class="petition_main" role="main">
    <section class="container">
        <div class="row">

            <?php $this->load->view('/nonprofits/_nonprofit_new_nav'); ?>

            <!--<div class="col-md-10 col-sm-9 clearfix">
                <h2 class="page_title pull-left">MISSIONS<?php if ($mission_count): ?> <small>(<span class="charity-review-count"><?php echo $mission_count; ?></span>)</small><?php endif; ?></h2>
            </div>-->

            <div class="col-md-10 col-sm-9">
                <div class="block">
                    <form class="edit-mission-form">
                        <textarea class="form-control edit-mission-textarea" placeholder="Add mission information about this nonprofit here..."><?php if ($my_mission): echo $my_mission->getMission(); endif; ?></textarea>
                        <label class="source-label" for="edit-mission-source">Source</label>
                        <input id="edit-mission-source" class="form-control edit-mission-source" type="text"
                               value="<?php echo $my_mission ? htmlspecialchars($my_mission->getSource()) : ''; ?>"
                               placeholder="Tell us where you got your information from. For example, the url of the nonprofit's &quot;About us&quot; or a website like Wikipedia...">

                        <button data-loading-text="SAVING..." data-charity-id="<?php echo $charity->getId(); ?>" type="button" class="btn btn-primary btn-mission-submit">SAVE</button>
                        <span id="saved-mission" class="hide">Saved...</span>
                    </form>
                </div>

                <div id="missions-container">
                    <?php $CI->load->view('/nonprofits/_missions', ['missions' => $missions]); ?>
                </div>
            </div>

        </div><!-- row end -->
    </section><!-- container end -->
</main>