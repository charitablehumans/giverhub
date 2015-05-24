<?php $CI =& get_instance(); ?>
<p class="lead txtCntr address-lead">Add Address</p>
<form id="frm-complete-profile" action="#">
    <div class="form-group">
        <input type="text" class="form-control" id="complete_address" name="completeAddress" placeholder="Address" value="" tabindex="3">
    </div>
    <div class="form-group">
        <input type="text" class="form-control" id="complete_address2" name="completeAddress2" placeholder="Address (optional)" value="" tabindex="4">
    </div>
    <div class="form-group">
        <input type="text" class="form-control" id="complete_zip" name="completeZipCode" placeholder="Zip code" value="" tabindex="5">
    </div>
    <div class="form-group">
        <select id="complete_state" name="completeState" class="form-control" tabindex="6">
            <option value="" selected="selected">Select State</option>
            <?php foreach ($CI->getStates() as $state): ?>
                <option value="<?php echo htmlspecialchars($state->getId()); ?>"><?php echo htmlspecialchars($state->getFullName()); ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-group">
        <img id="complete-profile-city-loader" class="hide" src="/images/ajax-loaders/ajax-loader.gif" alt="Loading cities..">
        <select id="complete_city" name="completeCity" class="form-control" tabindex="7">
            <option value="">City (Pick your state first)</option>
        </select>
    </div>
    <div class="form-group">
        <input type="text" class="form-control" id="complete_phone" name="completePhone" placeholder="Phone (Digits Only)" value="" tabindex="8">
    </div>
</form>
<div id="complete-profile-error-container" class="col-md-7">
    <div id="complete-profile-alert-success" class="alert alert-success complete-profile-alert hide">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <span><strong>Great!</strong> Address was saved.</span>
    </div>

    <div id="complete-profile-alert-danger" class="alert alert-danger complete-profile-alert hide">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <span id="complete-profile-alert-danger-msg">Unknown error</span>
    </div>
</div>