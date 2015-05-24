<?php
$CI =& get_instance();
/** @var \Base_Controller $CI */
/** @var \Entity\ActivityFeedPost $activity_feed_post */
/** @var string $context */
$post_is_from_me = $this->user && $activity_feed_post->getFromUserId() == $this->user->getId();
$post_is_to_me = $this->user && $activity_feed_post->getToUserId() == $this->user->getId();
?>
<tr class="activity-feed-post-tr" id="tr_activity_feed_post_<?php echo $activity_feed_post->getId(); ?>" data-activity-id="<?php echo get_class($activity_feed_post) . $activity_feed_post->getId(); ?>">
    <td class="activity <?php if ($context == 'my' && $post_is_from_me && !$post_is_to_me): ?>activity-is-from-me-to-other-user<?php endif; ?>" colspan="2">
        <?php if ($context == 'my'): ?>
            <div class="delete-activity-post">
                <button
                    type="button"
                    data-activity-post-id="<?php echo $activity_feed_post->getId(); ?>"
                    data-loading-text="hide"
                    data-container="body"
                    title="Hiding a post simply removes the notification from your own feed while leaving the post intact."
                    class="gh_tooltip btn btn-xs btn-primary btn-hide-activity-post">hide</button>
                <?php if ($post_is_from_me): ?>
                    <button
                        type="button"
                        data-activity-post-id="<?php echo $activity_feed_post->getId(); ?>"
                        data-loading-text="remove"
                        data-container="body"
                        title="Removing a post deletes it completely from the site."
                        class="gh_tooltip btn btn-xs btn-danger btn-delete-activity-post">remove</button>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <div class="date-div"><?php echo $activity_feed_post->getDateDt()->format('m/d/Y'); ?><br/><?php echo $activity_feed_post->getDateDt()->format('h:i A'); ?></div>

        <?php if ($context == 'my' && $post_is_from_me && !$post_is_to_me): ?>
            <div class="you-posted-a-comment">
                You posted a <a class="btn-post-comment-open" href="#">comment</a> to <?php echo $activity_feed_post->getToUser()->getLink(); ?>'s feed
            </div>
        <?php endif; ?>

        <div class="submitted-by-container <?php if ($context == 'my' && $post_is_from_me && !$post_is_to_me): ?>hidden-comment-to-other<?php endif; ?>">
            <div class="pull-left user-container">
                <a href="<?php echo $activity_feed_post->getFromUser()->getUrl(); ?>">
                    <img src="<?php echo $activity_feed_post->getFromUser()->getImageUrl(); ?>" alt="<?php echo htmlspecialchars($activity_feed_post->getFromUser()->getName()); ?>">
                    <span class="name"><?php echo htmlspecialchars($activity_feed_post->getFromUser()->getName()); ?></span>
                </a>
                <?php if ($context == 'my'): ?>
                    <?php if ($post_is_from_me && $post_is_to_me): ?>

                    <?php elseif ($post_is_from_me): ?>
                        <i class="glyphicon glyphicon-play"></i> <a href="<?php echo $activity_feed_post->getToUser()->getUrl(); ?>">
                            <img src="<?php echo $activity_feed_post->getToUser()->getImageUrl(); ?>" alt="<?php echo htmlspecialchars($activity_feed_post->getToUser()->getName()); ?>">
                            <span class="name"><?php echo htmlspecialchars($activity_feed_post->getToUser()->getName()); ?></span>
                        </a>
                    <?php elseif ($post_is_to_me): ?>

                    <?php else: ?>

                    <?php endif; ?>
                <?php elseif ($context == 'other'): ?>
                    <?php if ($post_is_from_me && $post_is_to_me): ?>

                    <?php elseif ($post_is_from_me): ?>

                    <?php elseif ($activity_feed_post->getToUserId() == $activity_feed_post->getFromUserId()): ?>

                    <?php else: ?>
                        <i class="glyphicon glyphicon-play"></i> <a href="<?php echo $activity_feed_post->getToUser()->getUrl(); ?>">
                            <img src="<?php echo $activity_feed_post->getToUser()->getImageUrl(); ?>" alt="<?php echo htmlspecialchars($activity_feed_post->getToUser()->getName()); ?>">
                            <span class="name"><?php echo htmlspecialchars($activity_feed_post->getToUser()->getName()); ?></span>
                        </a>
                    <?php endif; ?>
                <?php else: ?>
                    <?php throw new Exception('invalid context'); ?>
                <?php endif; ?>
            </div>
        </div>

        <?php $post_text = $activity_feed_post->getText(['link_external_urls' => true]); ?>
        <?php if ($context == 'my'): ?>
            <?php if ($post_is_from_me && $post_is_to_me): ?>
                <div class="content"><?php echo $post_text; ?></div>
            <?php elseif ($post_is_from_me): ?>
                <div class="content hidden-comment-to-other"><?php echo $post_text; ?></div>
            <?php elseif ($post_is_to_me): ?>
                <div class="content"><?php echo $post_text; ?></div>
            <?php else: ?>
                <div class="content"><?php echo $post_text; ?></div>
            <?php endif; ?>
        <?php elseif ($context == 'other'): ?>
            <?php if ($post_is_from_me && $post_is_to_me): ?>
                <div class="content"><?php echo $post_text; ?></div>
            <?php elseif ($post_is_from_me): ?>
                <div class="content"><?php echo $post_text; ?></div>
            <?php elseif ($activity_feed_post->getToUserId() == $activity_feed_post->getFromUserId()): ?>
                <div class="content"><?php echo $post_text; ?></div>
            <?php else: ?>
                <div class="content"><?php echo $post_text; ?></div>
            <?php endif; ?>
        <?php else: ?>
            <?php throw new Exception('invalid context'); ?>
        <?php endif; ?>

        <?php $external_url = $activity_feed_post->getExternalUrl(); ?>
        <?php if ($external_url && $external_url->getContentType() != 'invalid'): ?>
            <div class="external-url-container <?php if ($context == 'my' && $post_is_from_me && !$post_is_to_me): ?>hidden-comment-to-other<?php endif; ?>">
                <?php if ($external_url->getImageUrl()): ?>
                    <a class="image-link <?php echo $external_url->getImage() == 3 ? 'large' : '';?>" target="_blank" href="<?php echo $external_url->getUrl(); ?>">
                        <img alt="<?php echo $external_url->getUrl(); ?>" class="image" src="<?php echo htmlspecialchars($external_url->getImageUrl()); ?>">
                    </a>
                <?php endif; ?>
                <div class="content-wrapper <?php echo $external_url->getImage() == 3 ? 'large' : '';?>">
                    <p class="title"><a target="_blank" href="<?php echo htmlspecialchars($external_url->getUrl()); ?>"><?php echo $external_url->getContentType()=='image'?'Image':htmlspecialchars($external_url->getTitle()); ?></a></p>
                    <p class="desc"><?php echo $external_url->getContentType()=='image'?'-':htmlspecialchars($external_url->getDescription()); ?></p>
                    <p class="url"><a target="_blank" href="<?php echo htmlspecialchars($external_url->getUrl()); ?>"><?php echo htmlspecialchars($external_url->getUrl()); ?></a></p>
                </div>
            </div>
        <?php endif; ?>

        <?php $images =$activity_feed_post->getImages(); ?>
        <?php if ($images): ?>
            <div class="images <?php if ($context == 'my' && $post_is_from_me && !$post_is_to_me): ?>hidden-comment-to-other<?php endif; ?>">
                <?php foreach($images as $image): ?>
                    <a data-large-src="<?php echo $image->getUrl(); ?>" class="show-photo-in-modal" href="#">
                        <img alt="Attached Image" src="<?php echo $image->getThumbUrl(); ?>">
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php $youtube_videos =$activity_feed_post->getYoutubeVideos(); ?>
        <?php if ($youtube_videos): ?>
            <div class="youtube-videos <?php if ($context == 'my' && $post_is_from_me && !$post_is_to_me): ?>hidden-comment-to-other<?php endif; ?>">
                <?php foreach($youtube_videos as $youtube_video): ?>
                    <div class="youtube-video-wrapper">
                        <iframe
                            class="youtube-player youtube-iframe"
                            type="text/html"
                            width="560" height="315"
                            src="https://www.youtube.com/embed/<?php echo htmlspecialchars($youtube_video->getVideoId()); ?>?controls=2"
                            allowfullscreen
                            frameborder="0"></iframe>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if ($activity_feed_post->hasCharity()): ?>
            <?php $charity = $activity_feed_post->getCharity(); ?>
            <div class="nonprofit <?php if ($context == 'my' && $post_is_from_me && !$post_is_to_me): ?>hidden-comment-to-other<?php endif; ?>">
                <div class="nonprofit-name"><?php echo $charity->getLink(); ?></div>
                <?php if ($charity->getMissionSummary()): ?>
                    <div class="nonprofit-tagline" title="<?php echo htmlspecialchars($charity->getMissionSummary()); ?>">
                        <?php echo htmlspecialchars($charity->getMissionSummary()); ?>
                    </div>
                <?php endif; ?>
                <div class="donate-container">
                    <label id="activity-post-donate-amount-label" for="activity-post-donate-amount_<?php echo $activity_feed_post->getId(); ?>">Easy Donate: </label>
                    <input id="activity-post-donate-amount_<?php echo $activity_feed_post->getId(); ?>" type="text" class="form-control charity-profile-donation-amount-input" placeholder="$ Amount"/>
                    <a
                        data-charity-id="<?php echo htmlspecialchars($charity->getId()); ?>"
                        data-charity-name="<?php echo htmlspecialchars($charity->getName()); ?>"
                        href="#"
                        class="btn btn-warning btn-donate-from-charity-with-amount charity-page-donate-button clear-md">DONATE</a>
                </div>
            </div>
        <?php endif; ?>

        <?php if (true || $post_is_from_me): ?>
            <?php $extra_button = '<a href="#" data-post-url="'.$activity_feed_post->getUrl().'" class="btn-post-activity-feed-post-on-facebook">Share</a>'; ?>
        <?php else: ?>
            <?php $extra_button = '<div class="fb-like" data-href="'. $activity_feed_post->getUrl() .'" data-layout="button_count" data-action="like" data-show-faces="false" data-share="true"></div>'; ?>
        <?php endif; ?>

        <?php $CI->load->view('/members/_activity_feed_post_comments_container', ['entity' => $activity_feed_post, 'context' => $context, 'extra_button' => $extra_button]); ?>
    </td>
</tr>