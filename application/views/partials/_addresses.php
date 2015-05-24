<?php
/** @var \Base_Controller $CI */
$CI =& get_instance();
$user = $CI->user;
/** @var integer $selected_address_id */
if (!isset($selected_address_id) && $user->getDefaultAddressId()) {
    $selected_address_id = $user->getDefaultAddressId();
}
?>
<?php if (!function_exists('printAddress')): ?>
    <?php function printAddress(\Entity\UserAddress $address) { ?>
        <div class="address-row" data-user-address-id="<?php echo $address->getId(); ?>">

            <span class="address"><?php echo htmlspecialchars($address); ?></span>

            <span class="pull-right default">
                <a
                    data-user-address-id="<?php echo $address->getId(); ?>"
                    class="btn btn-primary btn-make-default-address btn-xs <?php if($address->isDefault()): ?>hide<?php endif; ?>">Make Default</a>
                <a
                    disabled="disabled"
                    class="btn btn-success btn-current-default-address btn-xs <?php if(!$address->isDefault()): ?>hide<?php endif; ?>">Current Default!</a>
            </span>

            <span class="pull-right edit">
                <a
                    data-user-address-id="<?php echo $address->getId(); ?>"
                    data-address1="<?php echo htmlspecialchars($address->getAddress1()); ?>"
                    data-address2="<?php echo htmlspecialchars($address->getAddress2()); ?>"
                    data-phone="<?php echo htmlspecialchars($address->getPhone()); ?>"
                    data-zipcode="<?php echo htmlspecialchars($address->getZipcode()); ?>"
                    data-state="<?php echo htmlspecialchars($address->getStateId()); ?>"
                    data-city="<?php echo htmlspecialchars($address->getCityId()); ?>"
                    class="btn btn-edit-address btn-primary btn-xs <?php if($address->isDefault()): ?>default-address<?php endif; ?>">Edit</a>
            </span>

            <span class="pull-right select">
                <a
                    data-user-address-id="<?php echo $address->getId(); ?>"
                    class="btn btn-primary btn-select-address btn-xs <?php if($address->isDefault()): ?>hide<?php endif; ?>">Select</a>
                <a
                    disabled="disabled"
                    class="btn btn-success btn-selected-address btn-xs <?php if(!$address->isDefault()): ?>hide<?php endif; ?>">Selected!</a>
            </span>

        </div>
    <?php } ?>
<?php endif; ?>

<?php if (isset($selected_address_id)): ?>
    <?php
        /** @var \Entity\UserAddress $selected */
        $selected = \Entity\UserAddress::find($selected_address_id);
        printAddress($selected);
    ?>
<?php endif; ?>

<?php $first = true; ?>
<?php foreach($user->getAddresses() as $address): ?>
    <?php if(!isset($selected) || $selected->getId() != $address->getId()): ?>
        <?php if ($selected_address_id && $first): ?>
            <hr/>
            <?php $first = false; ?>
        <?php endif; ?>
        <?php printAddress($address); ?>
    <?php endif; ?>
<?php endforeach; ?>