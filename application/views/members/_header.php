<?php
/** @var \Entity\User $user */
/** @var bool $my_dashboard */
if (!isset($my_dashboard)) $my_dashboard = true;
/** @var \Members $CI */
$CI =& get_instance();
?>

<section class="gh_secondary_header member-dashboard-header dashboard_image_uploading clearfix">

    <section class="container user_head">

        <div class="row user_head_row">

            <div class="col-sm-5 left_col">
                <div class="row left_col_row">
                    <div class="col-sm-5 col-xs-3">
                        <?php if ($my_dashboard): ?>
                            <form id="upload-profile-photo-form"
                                  class="gh_tooltip"
                                  title="Click to Change Profile Picture"
                                  data-placement="top"
                                  action="/upload/profile_photo"
                                  method="POST"
                                  enctype="multipart/form-data">
                                <img src="<?php echo $user->getImageUrl(); ?>" alt="<?php echo htmlspecialchars($user->getName()); ?>" class="img-rounded user_avatar" />
                                <p class="img-rounded user-avatar-progress hide">
                                    <span>0.0%</span>
                                    <img alt="Loading" src="/images/ajax-loaders/kit.gif"/>
                                </p>

                                <input type="file" name="profile-photo-input" accept="image/*">
                                <span class="glyphicon glyphicon-edit"></span>
                            </form>
                        <?php else: ?>
                            <img src="<?php echo $user->getImageUrl(); ?>" alt="<?php echo htmlspecialchars($user->getName()); ?>" class="img-rounded user_avatar" />
                        <?php endif; ?>
                    </div>
                    <div class="col-sm-7 col-xs-9">
                        <?php if(!$my_dashboard): ?>
                            <button type="button"
                                    data-user-id="<?php echo $user->getId(); ?>"
                                    data-loading-text="UNFOLLOW"
                                    class="btn btn-sm btn-info btn-member-dashboard-follow-user unfollow <?php if (!$CI->user->isFollowingUser($user)): ?>hide<?php endif; ?>">UNFOLLOW</button>
                            <button type="button"
                                    data-user-id="<?php echo $user->getId(); ?>"
                                    data-loading-text="FOLLOW"
                                    class="btn btn-sm btn-primary btn-member-dashboard-follow-user follow <?php if ($CI->user->isFollowingUser($user)): ?>hide<?php endif; ?>">FOLLOW</button>
                        <?php endif; ?>
						<div class="so-awesome"></div>
                        <div class="user_score">
                            <span class="givercoin-text">GiverCoin:</span>
                            <span class="big givercoin-value-update" data-user-id="<?php echo $user->getId(); ?>"><?php echo round($user->getScore(),2); ?></span>
                        </div>
                        <div class="badges-wrapper">
                            <button
                                class="bs3_popover about-badges-info"
                                data-placement="bottom"
                                data-trigger="hover"
                                data-content="Badges you are eligible to win are displayed below after you donate to non profits that belong to specific causes (e.g. Education, Arts, etc.). Progress bars appear when you hover over a badge to show you how close you are to winning that badge."
                                data-title="About Badges">i</button>
                            <?php $badges = $user->getBadges(); ?>
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

            <div class="col-sm-7 hidden-xs right_col" id="causes-wrapper">
                <?php $CI->load->view('/members/_causes', ['my_dashboard' => $my_dashboard, 'user' => $user]); ?>
            </div>

        </div>

    </section>
</section>

<?php
$CI->load->view('members/_all_my_badges_modal', array('badges' => $badges));

$CI->modal('add-categories-modal', [
    'header' => 'Causes',
    'modal_size' => 'col-md-6',
    'body' => '/modals/add-categories-modal-body',
    'body_string' => false,
    'body_data' => [
        'user' => $user,
        'my_dashboard' => $my_dashboard
    ],
    'footer' => $my_dashboard ?
        '<a class="btn btn-primary btn-add-categories-save">SAVE</a>
         <a data-dismiss="modal" class="btn">CANCEL</a>'
        :
        '<a data-dismiss="modal" class="btn btn-primary">OK</a>',
]);