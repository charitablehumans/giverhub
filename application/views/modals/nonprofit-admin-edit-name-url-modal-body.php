<?php
/** @var array $body_data */
/** @var \Entity\Charity $charity */
$charity = $body_data['charity'];
?>
<form>
    <label>
        Name: <input type="text" class="form-control name" value="<?php echo htmlspecialchars($charity->getName()); ?>">
        <div class="alert alert-danger name-error hide">unexpected problem</div>
    </label>
    <label>
        Url: https://giverhub.com/nonprofits/<input type="text" class="form-control url"  value="<?php echo htmlspecialchars($charity->getUrlSlug()); ?>">
        <div class="alert alert-danger url-error hide">unexpected problem</div>
    </label>
</form>
<p>Note: It will take up to 10 minutes before your new name is searchable on the site.</p>
<p>Note: Your old url will still work and redirect users to the new url.</p>

<div class="modal-footer">
    <button type="button"
            class="btn btn-reset"
            data-loading-text="Reset">Reset</button>
    <button type="button"
            class="btn btn-danger btn-cancel"
            data-loading-text="Cancel">Cancel</button>
    <button type="button"
            class="btn btn-success btn-save"
            data-charity-id="<?php echo $charity->getId(); ?>"
            data-loading-text="Save">Save</button>
</div>