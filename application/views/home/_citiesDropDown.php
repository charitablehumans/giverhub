<?php /** @var \Entity\CharityCity $cities */ ?>
<?php
/** @var Base_Controller $CI */
$CI = get_instance();
?>
<?php if (!empty($cities)): ?>
    <option value="">Select your city</option>
    <?php foreach ($cities as $city): /** @var Entity\CharityCity $city */ ?>
        <option <?php if ($CI->user && $CI->user->hasAddress() && $CI->user->getDefaultAddress()->getCityId() == $city->getId()): ?>selected="selected"<?php endif; ?> value="<?php echo $city->getId(); ?>"><?php echo htmlspecialchars($city->getName()); ?></option>
    <?php endforeach; ?>
<?php else: ?>
    <option value="">Pick your state first</option>
<?php endif; ?>