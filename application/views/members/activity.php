<?php
/** @var \Entity\User $user */
/** @var \Charity $CI */
$CI =& get_instance();
?>

<?php $this->load->view('/members/_header', array('user' => $user)); ?>

<main class="members_main" role="main" id="main_content_area">

    <section class="container">
        <div class="row">
            <!--<div class="col-md-12">
                <h2 class="page_title">RECENT ACTIVITY</h2>
            </div>-->

            <!-- Include new left side bar menu -->
            <?php //$this->load->view('/members/_member_new_nav', array('user' => $user)); ?>

            <div class="col-md-12">
                <div class="block">

                    <div class="table-responsive">
                        <?php $activities = $user->getActivityFeed(0, null, $CI->user->getId() == $user->getId() ? 'my' : 'other'); ?>
                        <?php if(empty($activities)): ?>
                            <h4>No activity.</h4>
                        <?php else: ?>
                            <table class="table table-hover activity-table big-activity-table">
                                <colgroup>
                                    <col class="activity">
                                    <col class="activity_cf_std">
                                </colgroup>
                                <tbody>
                                    <?php foreach($activities as $activity): ?>
                                        <?php $this->load->view('/members/_activity', array('activity' => $activity, 'context' => $CI->user->getId() == $user->getId() ? 'my' : 'other')); ?>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>

                        <?php endif; ?>
                    </div><!-- table-responsive end -->

                </div><!-- block end -->
            </div>

        </div><!-- row end -->
    </section><!-- container end -->
</main>