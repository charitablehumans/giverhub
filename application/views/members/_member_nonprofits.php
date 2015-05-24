<?php
/** @var \Entity\Charity[] $nonprofits */
/** @var int $total_results */
/** @var string $search_text */
/** @var \Base_Controller $CI */
$CI =& get_instance();
?>

<div class="row">
    <table class="table table-hover small-activity-table nonprofits-table">
        <tbody>
        <?php if (!$nonprofits): ?>
            Your search did not match any charity
        <?php else: ?>
            <?php foreach($nonprofits as $charity): ?>
                <?php $charityTitle = Common::truncate(htmlspecialchars($charity->getName()),40,"...",false,false); ?>
                <tr>
                    <td class="activity members_nonprofits_ftd">
                        <div class="gh_spacer_7 title-container nonprofits-display-area-text"><a href="<?php echo $charity->getUrl(); ?>"><?php echo $charityTitle; ?></a></div>

                        <?php if ($charity->getOverallScore() !== null): ?>
                            <div class="col-xs-12 col-md-12 clearfix gh_spacer_14 progress-container">
                                <div
                                    class="col-xs-8 col-md-9 progress progress-secondary"
                                    data-trigger="hover"
                                    data-placement="bottom"
                                    data-toggle="popover"
                                    data-content="<?php echo htmlspecialchars(\Entity\Charity::$overallScoreText); ?>">
                                    <div
                                        class="progress-bar progress-bar-success"
                                        role="progressbar"
                                        aria-valuenow="<?php echo round($charity->getOverallScore()); ?>"
                                        aria-valuemin="0"
                                        aria-valuemax="100"
                                        style="width:<?php echo $charity->getOverallScore(); ?>%"></div>
                                </div>
                                <div class="col-xs-1 col-md-2 progress-secondary-percent progress-bar-resize">
                                    <?php echo round($charity->getOverallScore()); ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="col-md-12 summary-container nonprofits-display-area-text" title="<?php echo htmlspecialchars($charity->getMissionSummary()); ?>">
                            <?php echo Common::truncate(htmlspecialchars($charity->getMissionSummary()),35,"..."); ?>
                        </div>
                    </td>
                    <td class="members_nonprofits_std">
                        <span>
                            <a
                                data-charity-name="<?php echo htmlspecialchars($charity->getName()); ?>"
                                data-charity-id="<?php echo $charity->getId(); ?>"
                                class="btn-donate-using-cc-paypal-button paypal gh-trigger-event gh_tooltip"
                                data-event-category="button"
                                data-event-action="click"
                                data-event-label="paypal donate (from nonprofit feed)"
                                title="Clicking this button will begin the process for making a donation using your paypal account. No donation will be made until you confirm your donation amount and click &quot;MAKE THE DONATION!&quot;"
                                data-container="body"
                                href="#"><img src="/img/button_paypal.png" alt="Donate to <?php echo htmlspecialchars($charity->getName()); ?> using Credit Card"></a>
                            <a
                                data-charity-name="<?php echo htmlspecialchars($charity->getName()); ?>"
                                data-charity-id="<?php echo $charity->getId(); ?>"
                                class="btn-donate-using-cc-paypal-button cc gh-trigger-event gh_tooltip"
                                data-event-category="button"
                                data-event-action="click"
                                data-event-label="cc donate (from nonprofit feed)"
                                title="Clicking this button will begin the process of making a donation using your credit card. No donation will be made until you confirm your donation amount and click &quot;MAKE THE DONATION!&quot;"
                                data-container="body"
                                href="#"><img src="/img/button_cc.png" alt="Donate to <?php echo htmlspecialchars($charity->getName()); ?> using Credit Card"></a>
                        </span>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>

    <?php if (!isset($total_results) || $total_results >= 4) : ?>
        <div class="col-xl-12 txtCntr">
            <div class="row view_more">
                <form class="non-profits_view" action="/search" method="post">
                    <a href="#" class="btn btn-default pull-right view_more_nonprofits">View More</a>
                    <input type="hidden" name="non-profit-tab" value="non-profit-tab">
                    <input type="hidden" name="search_text" class="search_text" value="<?php echo htmlspecialchars($search_text); ?>">
                </form>
            </div>
        </div>
    <?php endif; ?>
</div>


