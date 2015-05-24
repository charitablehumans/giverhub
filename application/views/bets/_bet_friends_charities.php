<?php
/** @var \Entity\Charity[] $charities */
?>
<?php if (!$charities): ?>
    No non-profits matching query..
<?php else: ?>
    <?php foreach($charities as $charity): ?>
        <li class="friend-challenge-search-result-li"
            data-charity-score="<?php echo round($charity->getOverallScore()); ?>"
            data-charity-tagline="<?php echo htmlspecialchars($charity->getMissionSummary()); ?>"
            data-charity-id="<?php echo $charity->getId(); ?>">
            <a
                class="select-charity"
                href="#"
                title="<?php echo htmlspecialchars($charity->getName()); ?>"><?php echo htmlspecialchars($charity->getName()); ?><br/><span class="desc"><?php echo htmlspecialchars($charity->getSearchDesc()); ?></span></a>
        </li>
    <?php endforeach; ?>
<?php endif; ?>