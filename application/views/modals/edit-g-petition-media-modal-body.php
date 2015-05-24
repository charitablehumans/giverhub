<?php
/** @var \Base_Controller $CI */
$CI =& get_instance();
/** @var array $body_data */
/** @var \Entity\Petition $petition */
$petition = $body_data['petition'];
?>
<p class="lead txtCntr">Update Media</p>
<div class="display-area">
    <form class="remove-g-petition-media-form" name="remove-g-petition-media-form" method="POST" enctype="multipart/form-data">
        <?php if ($petition->hasVideo()): ?>
            <?php echo $petition->getVideoHtml(); ?>
            <input type="hidden" name="giverhub-petition-media-type" id="giverhub-petition-media-type" value="video">
        <?php elseif ($petition->hasImage()): ?>
            <?php echo $petition->getImageHtml(); ?>
            <input type="hidden" name="giverhub-petition-media-type" id="giverhub-petition-media-type" value="image">
        <?php endif; ?>

        <div class="" style="margin-top:15px;">
            <a href="#"><img class="g-petition-remove-media" src="/images/remove-g-petition-media.png"></a>
            <a href="#"><img class="g-petition-update-media" src="/images/update-g-petition-media.png"></a>
        </div>
    </form>
</div>
<div class="update-area" style="display:none">
    <form action="/upload/petition_create" method="POST" enctype="multipart/form-data">

        <div class="center gh_spacer_14">
            <input class="temp-id" type="hidden" name="tempId" value="<?php echo crc32(rand()); ?>">
            <input type="hidden" name="g-petition-id" class="g-petition-id" value="<?php echo $petition->getId();?>">
            <div class="upl-btn">
                <button type="button" class="btn btn-primary upload-photo-btn">Upload Photo</button>
                <input type="file" class="petition-photo-input" name="petition-photo-input">
            </div>
            <img src="/images/ajax-loaders/bar.gif" class="upl-loading hide">
            <div class="upl-img hide">
                <img src="/images/ajax-loaders/bar.gif">
                <button type="button" class="btn btn-xs btn-danger btn-delete-create-giverhub-petition-image" data-loading-text="X">X</button>
            </div>
            or a link to a <span class="bold">Video</span> or <span class="bold">Photo</span>?
            <div class="add-url-container">
                <input class="form-control add-url-input" type="text" placeholder="http://"><button type="button" data-loading-text="ADD" class="add-url-btn">ADD</button>
            </div>
            <div class="alert add-url-alert alert-danger hide" role="alert">
                <span class="message"><strong>Oops!</strong> Something unexpected went wrong!</span>
            </div>
            <div class="add-url-preview hide"></div>
        </div>
        <div class="center">
            <a class="btn btn-primary noise btn-update-g-petition-media">SAVE</a>
            <a data-dismiss="modal" class="btn btn-cancel-g-petition-edit">CANCEL</a>
        </div>

    </form>
</div>