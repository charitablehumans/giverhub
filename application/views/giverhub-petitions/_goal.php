<?php /** @var \Entity\Petition $petition */ ?>
<?php if ($petition->getGoal()): ?>
    <?php echo $petition->getGoal(); ?>
<?php else: ?>
    <span class="no-goal-available">-</span>
<?php endif; ?>