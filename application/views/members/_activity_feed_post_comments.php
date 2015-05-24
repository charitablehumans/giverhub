<?php
/** @var \Entity\ActivityFeedPostComment[] $comments */
/** @var \Entity\ActivityFeedPost|\Entity\Challenge $entity */
/** @var \Base_Controller $CI */
$CI =& get_instance();
?>
<div id="activity_feed_post_comments_<?php echo $entity->get_class_without_namespace().$entity->getId(); ?>" class="activity-feed-comments-container">
    <?php $max_comments = 4; ?>
    <?php $comments_count = count($comments); ?>
    <?php if ($comments_count > $max_comments): ?>
        <a href="#" class="display-more-comments">View <?php echo $comments_count-$max_comments; ?> more comments.</a>
    <?php endif; ?>
    <?php foreach($comments as $nr => $comment): ?>
        <div class="comment-container <?php if ($nr < $comments_count-$max_comments): ?>hide<?php endif; ?>">
            <a class="profile-pic-a" title="<?php echo htmlspecialchars($comment->getUser()->getName()); ?>" href="<?php echo $comment->getUser()->getUrl(); ?>"><img alt="<?php echo htmlspecialchars($comment->getUser()->getName()); ?>" class="profile-pic" src="<?php echo $comment->getUser()->getImageUrl(); ?>"></a>
            <div class="mess">
                <a href="<?php echo $comment->getUser()->getUrl(); ?>" title="<?php echo htmlspecialchars($comment->getUser()->getName()); ?>"><?php echo htmlspecialchars($comment->getUser()->getName()); ?></a>
                <?php echo nl2br(htmlspecialchars($comment->getText())); ?>
                <?php $external_url = $comment->getExternalUrl(); ?>
                <?php if ($external_url && $external_url->getContentType() != 'invalid'): ?>
                    <div class="external-url-container">
                        <?php if ($external_url->getImageUrl()): ?>
                            <a target="_blank" href="<?php echo $external_url->getUrl(); ?>"><img alt="<?php echo $external_url->getUrl(); ?>" class="image" src="<?php echo htmlspecialchars($external_url->getImageUrl()); ?>"></a>
                        <?php endif; ?>
                        <div class="content-wrapper">
                            <p class="title"><a target="_blank" href="<?php echo htmlspecialchars($external_url->getUrl()); ?>"><?php echo htmlspecialchars($external_url->getTitle()); ?></a></p>
                            <p class="desc"><?php echo htmlspecialchars($external_url->getDescription()); ?></p>
                            <p class="url"><a target="_blank" href="<?php echo htmlspecialchars($external_url->getUrl()); ?>"><?php echo htmlspecialchars($external_url->getUrl()); ?></a></p>
                        </div>
                    </div>
                <?php endif; ?>

                <?php $youtube_videos =$comment->getYoutubeVideos(); ?>
                <?php if ($youtube_videos): ?>
                    <div class="youtube-videos">
                        <?php foreach($youtube_videos as $youtube_video): ?>
                            <div class="youtube-video-wrapper">
                                <iframe
                                    class="youtube-player youtube-iframe"
                                    type="text/html"
                                    width="250" height="150"
                                    src="https://www.youtube.com/embed/<?php echo htmlspecialchars($youtube_video->getVideoId()); ?>?controls=2"
                                    allowfullscreen
                                    frameborder="0"></iframe>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <div class="date"><?php echo $comment->getHumanizedDateDifference(); ?></div>
            </div>
            <?php if ($CI->user && $comment->getUser() == $CI->user): ?>
                <a class="delete-comment" data-comment-id="<?php echo $comment->getId(); ?>" href="#">x</a>
                <img alt="Loading" class="deleting-comment-indicator hide" src="/images/ajax-loaders/indicator2.gif">
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>