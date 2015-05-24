<?php
/** @var \Entity\Charity $charity */
/** @var \Base_Controller $CI */
$CI =& get_instance();
?>
<?php if ($CI->user): ?>
    <?php $existing_review = $charity->getUserReviewedVolunteeringOpportunities($CI->user); ?>
    <div
        data-charity-id="<?php echo $charity->getId(); ?>"
        data-reviews-count="<?php echo $charity->getVolunteeringOpportunitiesReviewsCount(); ?>"
        data-user-reviewed-already="<?php echo $existing_review ? 1 : 0; ?>"
        <?php if ($existing_review): ?>
            data-users-rating="<?php echo $existing_review->getRating(); ?>"
            data-users-review="<?php echo htmlspecialchars($existing_review->getReview()); ?>"
        <?php endif; ?>
        ng-controller="ReviewVolunteeringOpportunitiesController"
        class="block review-volunteering-opportunities-block">

        <h3 class="gh_block_title">Review Their Volunteering Events <i class="icon-leave-review pull-right"></i></h3>

        <div class="thanks-wrapper" ng-show="!editing">
            Great! Thank you for your review! <a href="" ng-click="editing = true">Edit your review!</a>
        </div>
        <div class="form-wrapper" ng-show="editing">
            <div class="rating-wrapper">YOUR RATING: <rating ng-model="rating" max="5" readonly="false"></rating></div>
            <form name="review_volunteering_opportunities_form" novalidate>
                <textarea required class="form-control" ng-model="review" placeholder="Enter your review..."></textarea>
                <button class="btn btn-primary" ng-disabled="review_volunteering_opportunities_form.$invalid || !rating || submitting" ng-click="submit()">SUBMIT</button>
                <span ng-show="!!review && !rating">Please give a rating above.</span>
            </form>
        </div>
        <div class="reviews-count-wrapper">
            <div class="pull-left">Reviews ({{ reviewsCount }})</div>
            <div class="pull-right"><a href="/volunteering-opportunities/<?php echo $charity->getUrlSlug(); ?>/reviews" ng-show="reviewsCount">VIEW ALL REVIEWS</a></div>
        </div>
    </div>
<?php endif; ?>