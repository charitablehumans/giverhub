<?php
    /** @var string $extra_button */
    /** @var string $context */
    /** @var \Entity\ActivityFeedPost|\Entity\Challenge $entity */
    /** @var \Base_Controller $CI */
    $CI =& get_instance();
    $comments = \Entity\ActivityFeedPostComment::getComments($entity);
?>
<div class="activity-like-share-comment-wrapper">
    <div class="activity-share-comment">
        <?php
            $buttons = [];

            if (!method_exists($entity,'getFullUrl')) {
                $buttons[] = '<a
                href="#"
                '.(method_exists($entity,'getFullUrl') ? 'data-entity-url="'.$entity->getFullUrl().'"' : '').'
                class="btn-activity-like"
                data-entity="'. $entity->get_class_without_namespace() . '"
                data-entity-id="'. $entity->getId() . '">'.($this->user&&\Entity\ActivityFeedPostLike::didUserLikeIt($this->user,$entity)?'Unlike':'Like').'</a>';
            }
            $buttons[] = '<a href="#" class="btn-activity-show-comments">Comment</a>';
        ?>
        <?php if ($extra_button): ?><?php $buttons[] = $extra_button; ?><?php endif; ?>
        <?php echo join(' · ', $buttons) . ' · '; ?>

        <?php $this->load->view('/members/_activity_feed_post_comment_share_like_indicators', ['entity' => $entity]); ?>

        <?php if (method_exists($entity,'getFullUrl')): ?>
            · <iframe class="fb-share-iframe" src="//www.facebook.com/plugins/like.php?href=<?php echo urlencode($entity->getFullUrl()); ?>&amp;width&amp;layout=button_count&amp;action=like&amp;show_faces=false&amp;share=true&amp;height=21&amp;appId=322090001225722"></iframe>
        <?php endif; ?>
    </div>

    <div class="activity-comments-wrapper <?php if (!$comments): ?>hide<?php endif; ?>">
        <?php $CI->load->view('/members/_activity_feed_post_comments', ['entity' => $entity, 'comments' => $comments]); ?>
        <?php if ($CI->user): ?>
            <div class="make-post-comment-container">
                <img src="<?php echo $CI->user->getImageUrl(); ?>" alt="<?php echo htmlspecialchars($CI->user->getName()); ?>"/>
                <textarea data-context="<?php echo $context; ?>" data-entity="<?php echo $entity->get_class_without_namespace(); ?>" data-entity-id="<?php echo $entity->getId(); ?>" class="make-post-comment-textarea" placeholder="Write a comment..."></textarea>
                <div class="youtube-preview-container"></div>
                <div class="external-url-preview-container hide"></div>
            </div>
        <?php endif; ?>
    </div>
</div>