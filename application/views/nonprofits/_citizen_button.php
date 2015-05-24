<?php
/** @var \Entity\Charity $charity */
/** @var Base_Controller $CI */
$CI =& get_instance();
?>
<button data-charity-id="<?php echo $charity->getId(); ?>" type="button" class="btn btn-info btn-xs btn-admin-citizen" data-loading-text="ci">ci</button>