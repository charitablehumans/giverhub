<?php
/** @var \Entity\Page $page */
/** @var \Page $CI */
$CI =& get_instance();
?>

<section class="gh_secondary_header member-dashboard-header dashboard_image_uploading clearfix">
    <section class="container user_head">

        <div class="row user_head_row">

            <div class="col-sm-5 left_col">
                <div class="row left_col_row">
                    <div class="col-sm-5">
                        <?php if ($page->isAdmin()): ?>
                            <form id="upload-page-logo-form" action="/upload/page_logo" method="POST" enctype="multipart/form-data">
                                <img src="<?php echo $page->getLogoUrl(); ?>" alt="<?php echo htmlspecialchars($page->getName()); ?>" class="img-rounded user_avatar" />
                                <p class="img-rounded user-avatar-progress hide">
                                    <span>0.0%</span>
                                    <img alt="Loading" src="/images/ajax-loaders/kit.gif"/>
                                </p>

                                <input type="hidden" name="page-id" value="<?php echo $page->getId(); ?>">
                                <input type="file" name="page-logo-input" accept="image/*">
                                <a href="#" class="change-page-logo-text">Change Logo</a>
                            </form>
                        <?php else: ?>
                            <img src="<?php echo $page->getLogoUrl(); ?>" alt="<?php echo htmlspecialchars($page->getName()); ?>" class="img-rounded user_avatar" />
                        <?php endif; ?>
                    </div>
                    <div class="col-sm-7 left_col_inner_right_col">
                        <div class="user_score">
                            <span class="givercoin-text">GiverCoin:</span>
                            <span class="big givercoin-value-update" data-user-id="<?php echo $page->getUser()->getId(); ?>"><?php echo round($page->getUser()->getScore(),2); ?></span>
                        </div>
                        <div class="badges-wrapper">
                            <button
                                class="bs3_popover about-badges-info"
                                data-placement="bottom"
                                data-trigger="hover"
                                data-content="Badges you are eligible to win are displayed below after you donate to non profits that belong to specific causes (e.g. Education, Arts, etc.). Progress bars appear when you hover over a badge to show you how close you are to winning that badge."
                                data-title="About Badges">i</button>
                            <?php $badges = $page->getUser()->getBadges(); ?>
                            <?php if (!$badges): ?>
                                <div id="badges-will-appear-text">Badges will appear here once you've donated, reviewed, created keywords and more.</div>
                            <?php else: ?>
                                <ul class="user_badges hide-on-medium-resolution">
                                    <?php foreach(array_slice($badges, 0, 3) as $badge): ?>
                                        <?php /** @var \Entity\Badge $badge */ ?>
                                        <li>
                                            <a class="badget<?php echo $badge->getCategoryId(); ?> ttip"
                                               data-html='true'
                                               data-content='<div class="progress progress-sm gh_spacer_7 txtCntr   ">
                                                                                      <div class="progress-bar" role="progressbar" aria-valuenow="<?php echo max(1,min($badge->getPoints()/1000, 100)); ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo max(1,min($badge->getPoints()/1000,100)); ?>%;">
                                                                                      </div>
                                                                                    </div><div class="col-md-12 txtCntr"><?php echo htmlspecialchars($badge->getCategory()->getName());?></div>'
                                               data-trigger='hover'
                                               data-placement='bottom'></a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                            <?php if (count($badges) > 3): ?>
                                <div class="user_badges_head">
                                    <a href="#" data-toggle="modal" data-target="#all-my-badges-modal">VIEW ALL BADGES</a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-7 right_col" id="description-wrapper">

                    <p class="description"><?php echo $page->getDescription(); ?></p>
                    <?php if ($page->isAdmin()): ?>
                        <button class="btn btn-xs btn-primary btn-edit-page-desc">Edit Description</button>
                    <?php endif; ?>

            </div>

        </div>

    </section>
</section>

<?php $CI->load->view('members/_all_my_badges_modal', array('badges' => $badges)); ?>

<?php if ($page->isAdmin()): ?>
    <?php $CI->modal('edit-page-desc-modal', [
        'header' => 'Description',
        'modal_size' => 'col-md-5',
        'body' => '<p class="lead txtCntr">Edit Description</p>
                    <form id="edit-page-description-form" action="#">
                        <div class="form-group">
                            <textarea data-initial-value="'.htmlspecialchars($page->getDescription()).'"
                            placeholder="Enter your description here..."
                            class="form-control description-textarea"
                            name="description"
                            tabindex="1"></textarea>
                        </div>
                    </form>',
        'body_string' => true,
        'footer' => '<button data-page-id="'.$page->getId().'" type="button" class="btn btn-primary btn-save-page-description" data-loading-text="SAVE" tabindex="2">SAVE</button>',
    ]); ?>
<?php endif; ?>