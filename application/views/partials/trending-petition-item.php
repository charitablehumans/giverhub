<?php /** @var \Entity\ChangeOrgPetition $petition */ ?>
<div class="petition-wrapper" data-stat-name="<?php echo 'trend-pet-'.$petition->getId(); ?>">
    <?php echo $petition->getLink(); ?>
    <?php if ($petition->hasMedia()): ?>
        <?php echo $petition->getMediaHtml(); ?>
    <?php endif; ?>
    <div class="summary ellipsis <?php echo $petition->hasMedia() ? 'has-img' : ''; ?>"><?php echo $petition->getOverview(); ?></div>
    <?php $this->load->view('/partials/stat', ['name' => 'trend-pet-'.$petition->getId()]); ?>
</div>