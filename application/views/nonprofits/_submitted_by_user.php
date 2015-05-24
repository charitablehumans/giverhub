<?php /** @var \Entity\User $user */ ?>
<div class="submitted-by pull-left">Submitted by</div>
<div class="pull-left user-container">
    <a href="<?php echo $user->getUrl(); ?>">
        <img src="<?php echo $user->getImageUrl(); ?>" alt="<?php echo htmlspecialchars($user->getName()); ?>"/>
        <span class="name"><?php echo $user->getName(); ?></span>
    </a>
</div>