<?php
/** @var \Base_Controller $CI */
$CI =& get_instance();
/** @var \Entity\User[] $followers */
/** @var \Entity\User[] $following */
?>

<?php $this->load->view('/members/_header', array('user' => $user)); ?>

<main class="members_main" role="main" id="main_content_area">

    <section class="container">
        <div class="row">

            <?php //$this->load->view('/members/_member_new_nav', array('user' => $user)); ?>

            <div class="col-md-12 clearfix">
                <div class="row">
                    <h2 class="col-md-6 col-sm-6 followers page_title_members">FOLLOWING YOU</h2>
                    <h2 class="col-md-6 col-sm-6 followers page_title_members">YOU'RE FOLLOWING</h2>
                </div>
                <div class="row">
                    <div id="follower-content-wrapper" class="clearfix paginate" data-pagination="user-follower-row">
                        <div id="follower-wrapper">
                            <?php $rowCount = max(count($followers), count($following)); ?>
                            <?php $displayedNoFollowers = false; $displayedNoFollowing = false; ?>
                            <?php for($x = 0; $x < ($rowCount ? $rowCount : 1) ; $x++): ?>
                                <div class="col-md-12 col-sm-12 followers user-follower-row clearfix">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 user-follower">
                                            <?php if (!$followers && !$displayedNoFollowers): ?>
                                                <?php $displayedNoFollowers = true; ?>
                                                You have no followers yet.
                                            <?php elseif(isset($followers[$x])): ?>
                                                <?php $this->load->view('nonprofits/_charity_follower_item', array('user' => $followers[$x])); ?>
                                            <?php endif; ?>
                                        </div><!-- col end -->

                                        <div class="col-md-6 col-sm-6 user-follower">
                                            <?php if (!$following && !$displayedNoFollowing): ?>
                                                <?php $displayedNoFollowing = true; ?>
                                                You're not following anyone yet.
                                            <?php elseif(isset($following[$x])): ?>
                                                <?php $this->load->view('nonprofits/_charity_follower_item', array('user' => $following[$x])); ?>
                                            <?php endif; ?>
                                        </div><!-- col end -->
                                    </div>
                                </div>
                            <?php endfor; ?>
                        </div><!-- clearfix end -->
                    </div><!-- clearfix end -->
                </div>
            </div>

        </div><!-- row end -->
    </section><!-- container end -->

</main>
