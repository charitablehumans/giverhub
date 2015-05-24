<?php
/** @var \Entity\Charity $charity */
/** @var \Charity $CI */
$CI =& get_instance();

/** @var \Entity\User[] $followers */
?>

<?php $this->load->view('/nonprofits/_charity_header', array('charity' => $charity)); ?>

<main class="petition_main" role="main">

    <section class="container clearfix">
        <div class="row">

            <?php $this->load->view('/nonprofits/_nonprofit_new_nav'); ?>

            <!--<div class="col-md-12">
                <h2 class="page_title">FOLLOWERS</h2>
            </div>-->


            <div id="follower-content-wrapper" class="col-md-10 col-sm-9 followers clearfix paginate" data-pagination="charity-follower-row">
                <div class="row">
                    <div id="follower-wrapper">

                    <?php if (!$followers): ?>
                        <!-- No followers Message -->

                        <div class="block user_info">
                            No Followers Yet !
                        </div><!-- block end -->

                    <?php else: ?>

                        <?php $row = true; ?>
                        <?php foreach ($followers as $follower): ?>
                            <?php if (!$follower): ?><?php continue; ?><?php endif; ?>
                            <?php if ($row): ?>
                                <div class="charity-follower-row clearfix">
                            <?php endif; ?>

                            <!-- COL #1 -->
                            <div class="col-md-6 charity-follower">
                                <?php $this->load->view('nonprofits/_charity_follower_item', array('user' => $follower)); ?>
                            </div><!-- col end -->

                            <?php if (!$row) : ?>
                                </div><!-- col end -->
                            <?php endif; ?>
                            <?php $row = !$row; ?>
                        <?php endforeach; ?>

                        <?php /* If loop ends at odd number */ if(!$row) : ?></div><!-- col end --><?php endif;?>
                    <?php endif; ?>
                </div>
            </div>
        </div><!-- clearfix end -->

    </section><!-- container end -->
</main>
