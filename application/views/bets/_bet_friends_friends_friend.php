<?php
    /** @var \Entity\User $user */
    /** @var boolean $selected */
    if (!isset($selected)) {
        $selected = false;
    }
?>
<li data-facebook-friend="0" data-user-id="<?php echo $user->getId(); ?>" data-fb-id="<?php echo $user->getFbUserId(); ?>">
    <a class="select-friend" href="#"><img alt="<?php echo htmlspecialchars($user->getName()); ?>" src="<?php echo $user->getImageUrl(); ?>"><?php echo htmlspecialchars($user->getName()); ?></a>
    <?php if ($selected): ?>
        <button type="button" class="btn btn-xs btn-danger btn-clear-friend">x</button>
    <?php endif; ?>
</li>