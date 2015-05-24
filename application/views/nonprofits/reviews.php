<?php
/** @var \Entity\Charity $charity */
/** @var \Charity $CI */
$CI =& get_instance();
?>

<?php $this->load->view('/nonprofits/_charity_header', array('charity' => $charity)); ?>

<main class="petition_main" role="main">
    <section class="container">
        <div class="row">

            <?php $this->load->view('/nonprofits/_nonprofit_new_nav'); ?>

            <?php
                $reviewChunks = $charity->hasReviews() ?
                    array_chunk($charity->getReviews(), ceil(count($charity->getReviews())/2)) :
                    array(0 => array(), 1 => array());

                /** @var \Entity\CharityReview[] $col1Chunk */
                $col1Chunk = $reviewChunks[0];
                /** @var \Entity\CharityReview[] $col2Chunk */
                $col2Chunk = isset($reviewChunks[1]) ? $reviewChunks[1] : array();
            ?>
            <!-- COL #1 -->
            <div class="col-md-10 col-sm-9">
                <div class="row">
                    <div class="charity-reviews-col1 col-md-6">
                        <?php foreach($col1Chunk as $review): ?>
                            <?php $this->load->view('nonprofits/_charity_review', array('review' => $review)); ?>
                        <?php endforeach; ?>
                    </div>

                    <!-- COL #2 -->
                    <div class="col-md-6">
                        <?php foreach($col2Chunk as $review): ?>
                            <?php $this->load->view('nonprofits/_charity_review', array('review' => $review)); ?>
                        <?php endforeach; ?>

                        <div class="block">
                            <h3 class="gh_block_title">Leave a Review <i class="icon-leave-review pull-right"></i></h3>

                            <form action="#" method="post" class="gh_leave_review gh_block_section gh_spacer_21" id="review-submit-form" style="padding-top:0;">
                                <div class="gh_spacer_14">
                                    <small class="color_light pull-left" style="margin-top:5px; margin-right:15px;">YOUR RATING:</small>
                                    <div class="rating rate">
                                        <a class="icon-star" href="#"></a>
                                        <a class="icon-star" href="#"></a>
                                        <a class="icon-star" href="#"></a>
                                        <a class="icon-star" href="#"></a>
                                    </div>
                                </div>
                                <!-- task 00365 -->
                                <div class="form-group clearfix">
                                    <textarea <?php if (!$this->user || $this->user->hasReviewedCharity($charity)): ?>disabled="disabled"<?php endif; ?>
                                              id="leave-review-textarea"
                                              name="review-desc"
                                              class="form-control"
                                              style="height:110px !important; resize:none;"
                                              data-initial-value="<?php if ($this->user && $this->user->hasReviewedCharity($charity)): ?>Thank you for your review!<?php endif; ?>"
                                              placeholder="Leave a review..."></textarea>
                                </div>
                                <!-- form-group end -->
                                <div id="review-message-container" class="alert alert-danger">
                                    <span id="review-message"></span>
                                </div>

                                    <button type="submit"
                                            data-loading-text="Saving.."
                                            class="btn btn-primary gh_spacer_21"
                                            <?php if ($CI->user && $CI->user->hasReviewedCharity($charity)): ?>disabled="disabled"<?php endif; ?>
                                            id="review-form-submit">SUBMIT</button>
                                    <button
                                        type="button"
                                        data-current-review="<?php echo $CI->user && $CI->user->hasReviewedCharity($charity) ? htmlspecialchars($CI->user->getCharityReview($charity)->getText()) : ''; ?>"
                                        data-current-rating="<?php echo $CI->user && $CI->user->hasReviewedCharity($charity) ? htmlspecialchars($CI->user->getCharityReview($charity)->getRating()) : ''; ?>"
                                        class="btn btn-success gh_spacer_21 btn-edit-nonprofit-review <?php if (!$CI->user || !$CI->user->hasReviewedCharity($charity)): ?>hide<?php endif; ?>">EDIT</button>

                                    <button
                                        type="button"
                                        data-loading-text="REMOVE"
                                        data-charity-id="<?php echo $charity->getId(); ?>"
                                        data-review-info-id="<?php echo $CI->user && $CI->user->hasReviewedCharity($charity) ? '#review-id-'.$CI->user->getCharityReview($charity)->getId() : ''; ?>"
                                        class="btn btn-danger gh_spacer_21 btn-remove-nonprofit-review <?php if (!$CI->user || !$CI->user->hasReviewedCharity($charity)): ?>hide<?php endif; ?>">REMOVE</button>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- row end -->
    </section><!-- container end -->
</main>