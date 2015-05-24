<?php
/** @var \Base_Controller $CI */
$CI =& get_instance();
/** @var string $currentTabName */
/** @var \Entity\Petition $petition */
?>
<div class="col-md-2 col-sm-3">
    <div class="col-sm-12 col-xs-12 members_left_menu menu1">
        <div class="block">
            <table class="table-hover" style="width: 100%">
                <tr>
                    <td class="new_nav_td">
                        <a class="use-in-mobile-menu" data-mobile-menu-label="PETITION" href="<?php echo $petition->getUrl(); ?>">Overview</a>
                    </td>
                </tr>
                <tr>
                    <td class="new_nav_td">
                        <a class="use-in-mobile-menu" data-mobile-menu-label="PETITION" href="<?php echo $petition->getUrl(); ?>/signatures<?php echo isset($_GET['from']) ? '?from='.$_GET['from'] : null; ?>">Signatures</a>
                    </td>
                </tr>
                <tr>
                    <td class="new_nav_td">
                        <a class="use-in-mobile-menu" data-mobile-menu-label="PETITION" href="<?php echo $petition->getUrl(); ?>/reasons<?php echo isset($_GET['from']) ? '?from='.$_GET['from'] : null; ?>">Reasons</a>
                    </td>
                </tr>
                <tr>
                    <td class="new_nav_td">
                        <a class="use-in-mobile-menu" data-mobile-menu-label="PETITION" href="<?php echo $petition->getUrl(); ?>/news<?php echo isset($_GET['from']) ? '?from='.$_GET['from'] : null; ?>">News</a>
                    </td>
                </tr>
                <?php if ($CI->user && $CI->user->getLevel() >= 4): ?>
                    <tr>
                        <td class="new_nav_td">
                            <a class="use-in-mobile-menu btn-feature-petition" data-mobile-menu-label="PETITION" href="#">Feature</a>
                        </td>
                    </tr>
                <?php endif; ?>
                <tr>
                    <td class="new_nav_td fb-share-container">
                        <a data-petition-id="<?php echo $petition->getId(); ?>" class="fb-share-g-petition-wrapper" href="#"></a>
                        <div class="fb-share-button" data-href="<?php echo current_url(); ?>" data-layout="button_count"></div>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
