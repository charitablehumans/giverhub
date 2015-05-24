<?php
/** @var \Entity\ChangeOrgPetition[] $petitions */
/** @var int $total_results */
/** @var string $search_text */
?>
<div class="row">
    <table class="petition-feed-table table table-hover">
        <tbody>
        <?php if (!$petitions): ?>
            Your search did not match any petition
        <?php else: ?>
            <?php foreach($petitions as $petition): ?>
                <?php $petitionTitle = Common::truncate(htmlspecialchars($petition->getTitle()),100," (...)",false,false); ?>
                <tr>
                    <td class="activity">
                        <strong>
                            <a href="/petitions/<?php echo $petition->getGiverhubUrlSlug(); ?>"><?php echo $petitionTitle; ?></a>
                        </strong>
                    </td>
                    <td>
                        <?php $user = \Base_Controller::$staticUser; ?>
                        <?php if ($petition->getStatus() != 'open'): ?>
                            <p>This petition is no longer open for signing.</p>
                        <?php elseif($petition->hasEnded()): ?>
                            <p>This petition has ended.</p>
                        <?php elseif($user && $user->hasSignedPetition($petition)): ?>
                            <p>You already signed this petition.</p>
                        <?php elseif(!$petition->getAuthKey() && $user): ?>
                            <p>This petition can only be signed from change.org. <a target="_blank" href="<?php echo $petition->getUrl(); ?>">Click here</a>.</p>
                        <?php else: ?>
                            <a
                                href="#"
                                class="btn btn-danger bold btn-sign-petition-overview pull-right gh-trigger-event"
                                data-event-category="button"
                                data-event-action="click"
                                data-event-label="Sign Petition (petition feed)"
                                data-petition-url="<?php echo $petition->getGiverhubUrl(base_url()); ?>"
                                data-loading-text="SIGN..."
                                data-petition-id="<?php echo $petition->getId(); ?>">SIGN</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
    <div class="change-org-disc-cont shrink-line-height">These petitions are powered by Change.org. By signing, you accept Change.org's <a href="http://www.change.org/about/terms-of-service" target="_blank">terms of service</a> and <a href="http://www.change.org/about/privacy" target="_blank">privacy policy</a>.</div>
    <?php if (!isset($total_results) || $total_results >= 4): ?>
        <div class="col-xl-12 txtCntr">
            <div class="row view_more">
                <form class="petition_view" action="/search" method="post">
                    <a href="#" class="btn btn-default pull-right view_more_petitions">View More</a>
                    <input type="hidden" name="petitions-tab" value="petitions-tab">
                    <input type="hidden" name="search_text" class="search_text" value="<?php echo htmlspecialchars($search_text); ?>">
                </form>
            </div>
        </div>
    <?php endif; ?>
</div>

