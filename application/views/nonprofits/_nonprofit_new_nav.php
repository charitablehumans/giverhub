<?php
/** @var \Base_Controller $CI */
$CI =& get_instance();
/** @var string $currentTabName */
/** @var \Entity\ChangeOrgPetition $petition */
?>
<div class="col-md-2 col-sm-3">
    <div class="row">
        <div class="col-sm-12 col-xs-12 hidden-xs members_left_menu menu1">
            <div class="block">
                <table class="table-hover" style="width: 100%">
                    <tr><td class="new_nav_td"><a class="use-in-mobile-menu" data-mobile-menu-label="NONPROFIT" href="<?php echo $charity->getUrl(); ?>">Overview</a></td></tr>
                    <?php if ($CI->user && $CI->user->isCharityAdmin($charity)): ?>
                        <tr><td class="new_nav_td"><a class="use-in-mobile-menu" data-mobile-menu-label="NONPROFIT" href="<?php echo $charity->getUrl(); ?>/messages">Messages</a></td></tr>
                    <?php endif; ?>
                    <tr><td class="new_nav_td"><a class="use-in-mobile-menu" data-mobile-menu-label="NONPROFIT" href="<?php echo $charity->getUrl(); ?>/reviews">Review</a></td></tr>
                    <tr><td class="new_nav_td"><a class="use-in-mobile-menu" data-mobile-menu-label="NONPROFIT" href="<?php echo $charity->getUrl(); ?>/followers">Followers</a></td></tr>
                    <tr><td class="new_nav_td"><a class="use-in-mobile-menu" data-mobile-menu-label="NONPROFIT" href="<?php echo $charity->getUrl(); ?>/missions">Missions</a></td></tr>
                    <tr><td class="new_nav_td"><a class="use-in-mobile-menu" data-mobile-menu-label="NONPROFIT" href="/volunteering-opportunities/<?php echo $charity->getUrlSlug(); ?>/">Volunteering</a></td></tr>
                    <?php if ($CI->user && $CI->user->getLevel() >= 4): ?>
                        <tr><td class="new_nav_td"><a class="use-in-mobile-menu" data-mobile-menu-label="NONPROFIT" href="<?php echo $charity->getUrl(); ?>/manage_keywords">Manage Keywords</a></td></tr>
                        <tr><td class="new_nav_td"><a class="btn-feature-charity use-in-mobile-menu" data-mobile-menu-label="NONPROFIT" href="#">Feature</a></td></tr>
                    <?php endif; ?>
                </table>
            </div>
        </div>
    </div>
</div>
<?php
$CI->modal('feature-charity-modal', [
    'header' => 'Feature Nonprofit',
    'modal_size' => 'col-md-6',
    'extra_attributes' => 'data-charity-id="'.$charity->getId().'" data-featured-text="'.htmlspecialchars($charity->getFeaturedText()).'" data-is-featured="'.$charity->getIsFeatured().'"',
    'body' => '<div class="form-group">
                    <label for="feature-this-charity-checkbox">Feature This Charity <input id="feature-this-charity-checkbox" type="checkbox"></label>
                </div>
                <div class="form-group">
                    <label for="featured-text-textarea">Additional Text</label>
                    <textarea rows="5" class="form-control" id="featured-text-textarea"></textarea>
                </div>',
    'body_string' => true,
    'footer' => '<a data-loading-text="Submitting..." href="#" class="btn btn-primary btn-submit-feature-charity-modal" aria-hidden="true">Submit</a>
                <a href="#" class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Cancel</a>',
    'footer_string' => true,
]);
$CI->modal('email-charity-modal', [
    'header' => 'Email Nonprofit',
    'modal_size' => 'col-md-6',
    'body' => '/modals/email-charity-modal-body',
    'body_string' => false,
    'body_wrapper' => false,
    'footer' => '<button id="email-charity-send" data-loading-text="Sending invitations..." type="button" class="btn btn-primary cntr">Send Invitation</button>',
    'footer_string' => true,
]);