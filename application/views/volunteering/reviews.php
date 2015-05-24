<?php
/** @var \Entity\CharityVolunteeringOpportunitiesReview[] $reviews */
/** @var \Entity\Charity $charity */
/** @var \Charity $CI */
$CI =& get_instance();
?>

<?php $this->load->view('/nonprofits/_charity_header', array('charity' => $charity)); ?>

<main class="petition_main" role="main">

    <section class="container">
        <div class="row">

            <?php $this->load->view('/nonprofits/_nonprofit_new_nav'); ?>

            <?php $this->load->view('volunteering/_reviews_list', array('reviews' => $reviews, 'charity' => $charity)); ?>

            <div class="col-md-10 col-sm-9">
                <div class="row">
                    <div class="col-md-6">
                        <?php $this->load->view('/volunteering/_volunteering_review_block', ['charity' => $charity]); ?>
                    </div>
                    <div class="col-md-6">

                    </div>
                </div>
            </div>

        </div><!-- row end -->
    </section><!-- container end -->
</main>