<?php
/** @var Entity\Charity $charity */
/** @var Base_Controller $CI */
$CI =& get_instance();
?>
<div class="charity-item block">
    <?php if ($CI->user && $CI->user->isAdmin()): ?>
        <?php $CI->load->view('/nonprofits/_citizen_button', ['charity' => $charity]); ?>
    <?php endif; ?>
    <h4 class="text-center"><a href="<?php echo $charity->getUrl(); ?>"><?php echo htmlspecialchars($charity->getName()); ?></a></h4>

    <div class="gh_spacer_21">
        <?php
        if ($charity->getOverallScore() !== null) {
            $progressBar = array();
            $progressBar['goalProgress'] = $charity->getOverallScore();
            $progressBar['tooltipMessage'] = \Entity\Charity::$overallScoreText;
            $progressBar['areaValueNow'] = round($charity->getOverallScore());
            $progressBar['subClasses'] = "gh_popover";
            $progressBar['type'] = 'charity-item';
            $this->view('/partials/_progress_bar',$progressBar);
        }
        ?>
    </div>

    <p class="short_description">
        <?php
            if ($charity->getIsFeatured() && $charity->getFeaturedText()) {
                echo nl2br(htmlspecialchars($charity->getFeaturedText()));
            } else {
                echo htmlspecialchars($charity->getMissionSummary());
            }
        ?>
    </p>


    <span>
        <a
            <?php if ($charity->hasFakeEin()): ?>
                disabled="disabled"
            <?php endif; ?>
            data-charity-name="<?php echo htmlspecialchars($charity->getName()); ?>"
            data-charity-id="<?php echo $charity->getId(); ?>"
            class="btn btn-warning btn-donate btn-donate-from-search donate-button gh-trigger-event"
            data-event-category="button"
            data-event-action="click"
            data-event-label="donate (from search result)"
            href="#">Donate</a>
    </span>
</div>

