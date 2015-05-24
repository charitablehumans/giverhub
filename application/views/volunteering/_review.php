<?php
/** @var \Entity\CharityVolunteeringOpportunitiesReview $review */
?>
<div class="block review_info">
    <div class="clearfix">
        <div class="col-md-6 rating gh_spacer_21">
            <span>RATING:</span>
            <?php for($x = 1; $x <= 5; $x++): ?>
                <i class="icon-star <?php if ($review->getRating() >= $x): ?>voted<?php endif;?>"></i>
            <?php endfor; ?>
        </div><!-- rating end -->

        <div class="col-md-6 date"><i class="icon-time"></i> <?php echo $review->getCreated()->format('F dS, Y'); ?></div>
    </div><!-- clearfix end -->

    <div class="gh_spacer_14">
        <p><?php echo nl2br(htmlspecialchars($review->getReview())); ?></p>
    </div>

    <span class="pull-right">Review by <a href="<?php echo $review->getUser()->getUrl();?>"><small><?php echo htmlspecialchars(strtoupper($review->getUser()->getName())); ?></small></a></span>

</div><!-- review_info end -->