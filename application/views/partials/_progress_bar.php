<?php
if(!isset($subClasses)) {
    $subClasses = "";
}
if(!isset($tooltipMessage)) {
    $tooltipMessage = "";
}
if(!isset($areaValueNow)) {
    $areaValueNow = "";
}
if(!isset($goalProgress)) {
    $goalProgress = "";
}

if (isset($type) && $type == 'petition-item') {
    $left_col = 'col-xs-7 col-md-7';
    $right_col = 'col-xs-5 col-md-5 petition-signatures-goal-val';
} else {
    $left_col = 'col-xs-10 col-md-10';
    $right_col = 'col-xs-2 col-md-2';
}
?>

<div
    class="clearfix gh_spacer_14 progress_bar <?php echo $subClasses; ?>"
    data-trigger="hover"
    data-placement="bottom"
    data-toggle="popover"
    data-html="true"
    data-content="<?php echo $tooltipMessage; ?>">
    <div class="<?php echo $left_col; ?> progress progress-secondary">
        <div
            class="progress-bar progress-bar-success noise"
            role="progressbar"
            aria-valuenow="<?php echo $areaValueNow; ?>"
            aria-valuemin="0"
            aria-valuemax="100" style="width:<?php echo $goalProgress; ?>%"></div>
    </div>

    <div class="<?php echo $right_col; ?> progress-secondary-percent progress-bar-resize">
        <?php echo $areaValueNow; ?>
    </div>
</div>