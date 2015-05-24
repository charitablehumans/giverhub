<?php
    /** @var \Entity\FacebookFriend $fb_friend */
    /** @var boolean $selected */
    if (!isset($selected)) {
        $selected = false;
    }
?>
<li data-facebook-friend="1" data-user-id="<?php echo $fb_friend->getId(); ?>" data-fb-id="<?php echo $fb_friend->getFbId(); ?>">
    <a class="select-friend" href="#">
        <img alt="<?php echo htmlspecialchars($fb_friend->getName()); ?>" src="<?php echo $fb_friend->getImageUrl(); ?>"><?php echo htmlspecialchars($fb_friend->getName()); ?>
        <span class="fb-friend-indicator pull-right">Facebook Friend</span>
    </a>
    <?php if ($selected): ?>
        <button type="button" class="btn btn-xs btn-danger btn-clear-friend">x</button>
    <?php endif; ?>
</li>