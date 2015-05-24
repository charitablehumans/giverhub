<?php
/** @var string $output */
/** @var \Base_Controller $CI */
$CI =& get_instance();
?>

<div class="row">
    <div class="column">
        <?php echo nl2br(htmlspecialchars($output)); ?>
    </div>
</div>
