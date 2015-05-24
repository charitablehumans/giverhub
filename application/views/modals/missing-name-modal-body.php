<?php $CI =& get_instance(); ?>
<p class="lead txtCntr">Now is a good time to let everybody know your name.</p>
<form id="frm-complete-profile" action="#">
    <div class="form-group">
        <input type="text" class="form-control" id="missing_first_name" name="f_name" placeholder="First name" value="<?php echo htmlspecialchars($CI->user->getFname()); ?>" tabindex="1">
    </div>
    <div class="form-group">
        <input type="text" class="form-control" id="missing_last_name" name="l_name" placeholder="Last name" value="<?php echo htmlspecialchars($CI->user->getLname()); ?>" tabindex="2">
    </div>
</form>