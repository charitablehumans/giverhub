<?php
$CI =& get_instance();
?>
<div class="from-to-container">
    <div class="from from-to vegur_light">
        <span class="from-to-label">From:</span>
        <div class="submitted-by-container">
            <div class="pull-left user-container">
                <a href="/">
                    <img src="<?php echo $givercard->getFromUser()->getImageUrl(); ?>" alt="<?php echo htmlspecialchars($givercard->getFromUser()->getName()); ?>">
                    <span class="name"><?php echo htmlspecialchars($givercard->getFromUser()->getName()); ?></span>
                </a>
            </div>
        </div>
    </div>
    <div class="to from-to vegur_light">
        <span class="from-to-label">To:</span>
        <div class="submitted-by-container">
            <div class="pull-left user-container">
                <a href="/">
                    <img src="<?php echo $givercard->getTo()->getImageUrl(); ?>" alt="<?php echo htmlspecialchars($givercard->getTo()->getName()); ?>">
                    <span class="name"><?php echo htmlspecialchars($givercard->getTo()->getName()); ?></span>
                </a>
            </div>
        </div>
    </div>
</div>
<p class="vegur_light">
    <?php if ($CI->user && $givercard->isFrom($CI->user)): ?>
        You have sent GiverCard to <?php echo htmlspecialchars($givercard->getTo()->getFname()); ?> with the message: <span class="vegur_regular"><?php echo htmlspecialchars($givercard->getMessage()); ?>!</span>
    <?php elseif ($CI->user && $givercard->isTo($CI->user)): ?>
        <?php echo htmlspecialchars($givercard->getFromUser()->getFname()); ?> has sent you a GiverCard with the message: <span class="vegur_regular"><?php echo htmlspecialchars($givercard->getMessage()); ?>!</span>
    <?php else: ?>
        <?php echo htmlspecialchars($givercard->getFromUser()->getFname()); ?> has sent GiverCard to <?php echo htmlspecialchars($givercard->getTo()->getName()); ?> with the message: <span class="vegur_regular"><?php echo htmlspecialchars($givercard->getMessage()); ?>!</span>
    <?php endif; ?>
</p>

