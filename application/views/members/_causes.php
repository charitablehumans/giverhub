<?php
/** @var \Entity\User $user */
/** @var integer $my_dashboard */
/** @var \Members $CI */
$CI =& get_instance();
if (!defined('CAUSE_LIMIT')) {
    define('CAUSE_LIMIT', 8);
}
?>

<?php if(!$user->getCauses()): ?>
    <div class="your-causes-container">
        <?php if ($my_dashboard): ?>
            YOUR CAUSES:
        <?php else: ?>
            <?php echo htmlspecialchars(strtoupper($user->getName())); ?>'s CAUSES
        <?php endif; ?>
    </div>

    <div class="empty-causes-container">
        <?php if ($my_dashboard): ?>
            <a href="#" class="add-categories-btn empty-causes">
                You have not added any causes yet.
                <u>Add causes</u> you’re passionate about to help us recommend nonprofits and petitions that suit your interests.
            </a>
        <?php else: ?>
            <span><?php echo htmlspecialchars($user->getName()); ?> has not selected any causes yet.</span>
        <?php endif; ?>
    </div>
<?php else: ?>
    <ul id="selected-categories-container" class="tag_cloud spacing-xs-resolutions" data-cause-limit="<?php echo CAUSE_LIMIT; ?>">
        <li class="your-causes">
            <?php if ($my_dashboard): ?>
                YOUR CAUSES:
            <?php else: ?>
                <?php echo htmlspecialchars(strtoupper($user->getName())); ?>'s CAUSES
            <?php endif; ?>
        </li>
        <?php $count = 0; ?>
        <?php foreach($user->getCategories() as $userCategory): ?>
            <?php $count++; ?>
            <li class="<?php if ($count > CAUSE_LIMIT): ?>hide<?php endif; ?>"><?php echo htmlspecialchars($userCategory->getCharityCategory()->getName()); ?>
                <?php if ($my_dashboard): ?>
                    <a  data-user-category-id="<?php echo $userCategory->getId(); ?>"
                        data-charity-category-id="<?php echo $userCategory->getCategoryId(); ?>"
                        class="icon-remove-sign btn-remove-user-category"
                        href="#"></a>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>

        <?php foreach($user->getCauses() as $userCause): ?>
            <?php $count++; ?>
            <li class="<?php if ($count > CAUSE_LIMIT): ?>hide<?php endif; ?>"><?php echo htmlspecialchars($userCause->getCharityCause()->getName()); ?>
                <?php if ($my_dashboard): ?>
                    <a  data-user-cause-id="<?php echo $userCause->getId(); ?>"
                        data-charity-cause-id="<?php echo $userCause->getCauseId(); ?>"
                        class="icon-remove-sign btn-remove-user-cause"
                        href="#"></a>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>
    <div class="show-more-causes">
        <a class="add-categories-btn" href="#" id="show-more-categories">View More<?php if ($my_dashboard): ?>/Edit/Add<?php endif; ?> Causes</a>
    </div>
<?php endif; ?>

