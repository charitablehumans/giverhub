<?php
/** @var array $urls */
?>
<?php if (!$urls): ?>
    <i>Nothing here yet?</i>
<?php endif; ?>
<?php foreach($urls as $url): ?>
    <?php $url = $url['url']; ?>
    <a href="<?php echo $url; ?>">https://giverhub.com<?php echo $url; ?></a><br/>
<?php endforeach; ?>