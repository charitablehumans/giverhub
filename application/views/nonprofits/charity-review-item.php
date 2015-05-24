<?php /** @var \Entity\CharityReview $review */ ?>
<div class="block review_info">
    <div class="clearfix">
        <div class="col-md-6 rating gh_spacer_21">
            <span>RATING:</span>
            <?php for($r = 1; $r <= 4; $r++): ?>
                <i class="icon-star <?php echo $review->getRating() >= $r ? 'voted' : '' ?>"></i>
            <?php endfor; ?>
        </div><!-- rating end -->

        <div class="col-md-6 date"><i class="icon-time"></i> <?php echo date('F jS, Y', strtotime($review->getTimeCreated())); ?></div>
    </div><!-- clearfix end -->

    <div class="gh_spacer_14">
        <p><?php echo htmlspecialchars($review->getReviewDesc()); ?></p>
    </div>
    <?php if (isset($showFrom) && $showFrom == true): ?>
        <a href="#" class="pull-right"><small>by <?php echo ucfirst($review->getUser()->getFname()).' '.ucfirst($review->getUser()->getLname()) ?></small></a>
    <?php elseif (isset($showTo) && $showTo == true): ?>
        <span class="pull-right">Reviewed <a href="<?php echo $review->getCharity()->getUrl(); ?>"><small><?php echo htmlspecialchars(strtoupper($review->getCharity()->getName())); ?></small></a></span>
    <?php endif; ?>
</div><!-- review_info end -->