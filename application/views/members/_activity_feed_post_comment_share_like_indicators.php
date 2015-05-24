<?php
    /** @var \Entity\ActivityFeedPost|\Entity\Challenge $entity */
    $likes = \Entity\ActivityFeedPostLike::getLikes($entity);
    $comments = \Entity\ActivityFeedPostComment::getComments($entity);
?>
<span class="comment-share-like-indicators">
    <?php if (!method_exists($entity,'getFullUrl')): ?><span class="glyphicon glyphicon-thumbs-up"><?php echo $likes; ?></span> · <?php endif; ?><span class="glyphicon glyphicon-comment"><?php echo count($comments); ?></span>
</span>