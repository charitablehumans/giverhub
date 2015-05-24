<?php
    /** @var \Entity\User $user */
    /** @var \Base_Controller $CI */
    $CI =& get_instance();
?>
<div class="block user_info">
    <div class="clearfix">
        <div class="col-sm-3 col-xs-5 avatar-container">
            <img src="<?php echo $user->getImageUrl(); ?>" alt="<?php echo htmlspecialchars($user->getName()); ?>" class="img-rounded" />
        </div>

        <div class="col-sm-9 col-xs-7 info">
            <h3>
                <?php echo $user->getLink(); ?>
            </h3>

            <?php $badges = $user->getBadges(); ?>
            <?php if ($badges): ?>
                <div class="col-sm-8 col-xs-8 rating badges gh_spacer_21">
                    <?php foreach($badges as $n => $badge): ?>
                        <?php if ($n > 2) { break; } ?>
                        <i class="rating_<?php echo $badge->getCategoryId(); ?>"></i>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>


            <div class="col-sm-4 col-xs-4 score">
                <small>GiverCoin:</small><br/>
                <span><?php echo round($user->getScore(),1); ?></span>
            </div>
        </div>
    </div><!-- clearfix end -->

    <footer>
        <div class="col-sm-6 col-xs-6">
            <?php $followedCharities = $user->getFollowedCharities(); ?>
            <?php if ($followedCharities): ?>
            <a class="charities ttip" href="#"
               data-html='true'
               data-content='<table class="table table-hover">
																          <tbody>
																            <?php foreach($followedCharities as $charity): ?>
                                                                                <tr>
                                                                                  <td class="col-md-7" style="border-top:none;"><strong><a href="<?php echo $charity->getUrl(); ?>"><?php echo htmlspecialchars($charity->getName(),ENT_QUOTES); ?></a></strong></td>
                                                                                  <td class="text-right" style="border-top:none;">
                                                                                      <div class="rating">
                                                                                        <?php for($x = 1; $x <= 4; $x++): ?>
                                                                                            <?php if($charity->getAverageReview() >= $x): ?>
                                                                                                <i class="icon-star voted"></i>
                                                                                            <?php else: ?>
                                                                                                <i class="icon-star"></i>
                                                                                            <?php endif; ?>
                                                                                        <?php endfor; ?>
                                                                                      </div>
                                                                                  </td>
                                                                                </tr>
																            <?php endforeach; ?>
																          </tbody>
																        </table>'
               data-trigger='click'
               data-placement='auto'>
                <span>CHARITIES</span>
                <i class="icon-heart"></i>
            </a>
            <?php endif; ?>
        </div>

        <?php if (!$CI->user || $CI->user->getId() != $user->getId()): ?>
            <div class="col-sm-6 col-xs-6 text-right btn-follow-user-container">
                    <a href="#" data-user-id="<?php echo $user->getId(); ?>"  class="btn-follow-user charity-followers follow_btn following <?php if (!$CI->user || !$CI->user->isFollowingUser($user)): ?>hide<?php endif; ?>" data-loading-text="UNFOLLOWING...">FOLLOWING <i class="icon-following"></i></a>
                    <a href="#" data-user-id="<?php echo $user->getId(); ?>" class="btn-follow-user charity-followers follow_btn not-following <?php if (!$CI->user || $CI->user->isFollowingUser($user)): ?>hide<?php endif; ?>" data-loading-text="FOLLOWING...">FOLLOW FRIEND <i class="icon-follower"></i></a>
            </div>
        <?php endif; ?>
    </footer>
</div><!-- block end -->