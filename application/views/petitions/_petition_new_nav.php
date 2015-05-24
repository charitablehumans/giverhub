<?php
/** @var \Base_Controller $CI */
$CI =& get_instance();
/** @var string $currentTabName */
/** @var \Entity\ChangeOrgPetition $petition */
?>
<div class="col-md-2 col-sm-3">
    <div class="col-sm-12 col-xs-12 members_left_menu menu1">
        <div class="block">
            <table class="table-hover" style="width: 100%">
                <tr><td class="new_nav_td"><a class="use-in-mobile-menu" data-mobile-menu-label="PETITION" href="/petitions/<?php echo $petition->getGiverhubUrlSlug(); ?><?php echo isset($_GET['from']) ? '?from='.$_GET['from'] : null; ?>">Overview</a></td></tr>
                <tr><td class="new_nav_td"><a class="use-in-mobile-menu" data-mobile-menu-label="PETITION" href="/petitions/<?php echo $petition->getGiverhubUrlSlug(); ?>/signatures<?php echo isset($_GET['from']) ? '?from='.$_GET['from'] : null; ?>">Signatures</a></td></tr>
                <tr><td class="new_nav_td"><a class="use-in-mobile-menu" data-mobile-menu-label="PETITION" href="/petitions/<?php echo $petition->getGiverhubUrlSlug(); ?>/reasons<?php echo isset($_GET['from']) ? '?from='.$_GET['from'] : null; ?>">Reasons</a></td></tr>
                <tr><td class="new_nav_td"><a class="use-in-mobile-menu" data-mobile-menu-label="PETITION" href="/petitions/<?php echo $petition->getGiverhubUrlSlug(); ?>/news<?php echo isset($_GET['from']) ? '?from='.$_GET['from'] : null; ?>">News</a></td></tr>
                <?php if ($CI->user && $CI->user->getLevel() >= 4): ?>
                    <tr><td class="new_nav_td"><a class="use-in-mobile-menu btn-feature-petition" data-mobile-menu-label="PETITION" href="#">Feature</a></td></tr>
                <?php endif; ?>
                <tr>
                    <td class="new_nav_td fb-share-container">
                        <a data-petition-id="<?php echo $petition->getId(); ?>" class="fb-share-petition-wrapper" href="#"></a>
                        <div class="fb-share-button" data-href="<?php echo current_url(); ?>" data-layout="button_count"></div>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
<?php
$CI->modal('feature-petition-modal', [
    'header' => 'Feature Petition',
    'extra_attributes' => 'data-petition-id="'.$petition->getId().'" data-featured-text="'.htmlspecialchars($petition->getFeaturedText()).'" data-is-featured="'.$petition->getIsFeatured().'"',
    'modal_size' => 'col-md-6',
    'body' => '<div class="form-group">
                    <label for="feature-this-petition-checkbox">Feature This Petition <input id="feature-this-petition-checkbox" type="checkbox"></label>
                </div>
                <div class="form-group">
                    <label for="featured-text-textarea">Additional Text</label>
                    <textarea rows="5" class="form-control" id="featured-text-textarea"></textarea>
                </div>',
    'body_string' => true,
    'footer' => '<a data-loading-text="Submitting..." href="#" class="btn btn-primary btn-submit-feature-petition-modal" aria-hidden="true">Submit</a>
                <a href="#" class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Cancel</a>'
]);