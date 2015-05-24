<?php
/** @var \Entity\User $user */
/** @var \Base_Controller $CI */
$CI =& get_instance();
?>
<div class="block create-petition-block">
    <div class="row">
        Create a petition?<br/>
        <button
            type="button"
            class="btn btn-primary btn-create-petition-from-block"
            data-temp-id="<?php echo crc32(rand()); ?>"
            data-loading-text="CREATE PETITION">CREATE PETITION</button>
    </div>
</div>

<div class="giverhub-petition-added-by-block" style="display:none">
	<div class="submitted-by-container">
		<div class="pull-left user-container">
		    <a href="<?php echo $user->getUrl(); ?>">
		        <img src="<?php echo $user->getImageUrl(); ?>" alt="<?php echo htmlspecialchars($user->getName()); ?>">
		        <span class="name"><?php echo htmlspecialchars($user->getName()); ?></span>
		    </a>
		</div>
	</div>
</div>

<div class="giverhub-petition-no-media-block" style="display:none">
	<img src="/images/camera_icon.png" alt="Media" >
</div>
