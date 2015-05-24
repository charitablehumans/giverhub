<?php /** @var \Entity\Petition $petition */ ?>
<?php if ($petition->getEndAt()): ?>
    <?php echo $petition->getEndAt()->format('m/d/y'); ?>
<?php else: ?>
    <span class="no-deadline-available">-</span>
<?php endif; ?>