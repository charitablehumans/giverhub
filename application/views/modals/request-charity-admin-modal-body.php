<?php
/** @var \Base_Controller $CI */
$CI =& get_instance();
/** @var array $body_data */
/** @var \Entity\Charity $charity */
$charity = $body_data['charity'];
?>
<form id="request-charity-admin-form">
    <?php if (!$CI->user): ?>
        <input type="text" class="form-control name" name="name" placeholder="Name">
        <input type="email" class="form-control email" name="email" placeholder="E-mail (preferably hosted on the nonprofit's domain)">
    <?php endif; ?>
    <textarea class="form-control message" name="message" placeholder="A brief explanation of why you should be given administrative control of this nonprofit`s GiverHub page. E.g. You are in charge of social media or marketing for the nonprofit"></textarea>
    <input type="hidden" name="charity_id" value="<?php echo $charity->getId(); ?>">
</form>
<div id="upload-request-charity-admin-image-container" class="hide">
    <img alt="Loading" id="upload-request-charity-admin-image-loading" src="/images/ajax-loaders/ajax-loader2.gif">
    <div id="request-charity-admin-images-container"></div>
</div>
<form id="upload-request-charity-admin-image-form" action="/upload/request_charity_admin_image" method="POST" enctype="multipart/form-data">
    <button type="button" class="btn btn-primary btn-sm"><img src="/assets/images/camera-glyph.png" class="camera-glyph" alt="Attach Images"/></button>
    <input id="upload-request-charity-admin-image-input" type="file" name="request-charity-admin-image-input" multiple>
    <input id="upload-request-charity-admin-image-form-temp-id" class="temp-id" type="hidden" name="tempId" value="<?php echo crc32(rand()); ?>">
    <label for="upload-request-charity-admin-image-input">Upload a photo ID or some photographic proof of your affiliation with the nonprofit</label>
</form>