<?php
/** @var \Base_Controller $CI */
$CI =& get_instance();
/** @var \Entity\User $user */
?>

<?php $this->load->view('/members/_header', array('user' => $user)); ?>

<main class="members_main" role="main" id="main_content_area">

    <section class="container">
        <div class="row">

            <?php

            $col1 = $col2 = '';
            $review_counter = 1;


            foreach ($user->getCharityReviews() as $review) {
                $reviewHtml = $this->load->view('/nonprofits/charity-review-item', array('review' => $review, 'showFrom' => false, 'showTo' => true), true);

                if ($review_counter % 2 == 1) {
                    $col1 = $reviewHtml;
                } else {
                    $col2 = $reviewHtml;
                }
                $review_counter++;
            } ?>


            <!-- COL #1 -->
            <!-- Include new left side bar menu -->
            <?php // $this->load->view('/members/_member_new_nav', array('user' => $user)); ?>

	        <div class="col-md-12">
		        <div class="row">
					<div class="col-md-6 col-sm-6">
			            <?php if ($col1): ?>
			                <?php echo $col1;  ?>
			            <?php else : ?>
			                <div class="block review_info">
			                    <div class="clearfix">
			                        <div class="col-md-6 rating gh_spacer_21">

			                        </div><!-- rating end -->

			                        <div class="col-md-6 date"></div>
			                    </div><!-- clearfix end -->

			                    <div class="gh_spacer_14">
			                        <p><b>You haven't reviewed any charities yet. You totally should! It helps other users plus you'll receive GiverCoin.</b></p>
			                    </div>


			                </div><!-- review_info end -->
			            <?php endif; ?>
			        </div>
			        <!-- COL #2 -->
			        <div class="col-md-6 col-sm-6">
			            <?php echo $col2; ?>
			        </div>
		        </div>
			</div>
        </div>
        <!-- block end -->
    </section><!-- container end -->

</main>
