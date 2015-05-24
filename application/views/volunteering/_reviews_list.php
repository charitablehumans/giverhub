<?php
/** @var \Entity\CharityVolunteeringOpportunitiesReview[] $reviews */
/** @var \Entity\Charity $charity */
?>
<?php
$reviewChunks = $reviews ? array_chunk($reviews, ceil(count($reviews)/2)) : [0 => [], 1 => []];

/** @var \Entity\CharityVolunteeringOpportunitiesReview[] $col1Chunk */
$col1Chunk = $reviewChunks[0];
/** @var \Entity\CharityVolunteeringOpportunitiesReview[] $col2Chunk */
$col2Chunk = isset($reviewChunks[1]) ? $reviewChunks[1] : array();
?>
<div class="col-md-10 col-sm-9 reviews-list-wrapper">
    <div class="row">
        <!-- COL #1 -->
        <div class="charity-reviews-col1 col-md-6">
            <?php foreach($col1Chunk as $review): ?>
                <?php $this->load->view('volunteering/_review', array('review' => $review)); ?>
            <?php endforeach; ?>
        </div>

        <!-- COL #2 -->
        <div class="col-md-6">
            <?php foreach($col2Chunk as $review): ?>
                <?php $this->load->view('volunteering/_review', array('review' => $review)); ?>
            <?php endforeach; ?>
        </div>
    </div>
</div>