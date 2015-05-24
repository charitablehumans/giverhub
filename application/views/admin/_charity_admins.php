<?php
/** @var \Entity\CharityAdmin[] $admins */
/** @var \Base_Controller $CI */
$CI =& get_instance();
?>
<?php foreach($admins as $admin): ?>
<tr>
    <td><a href="mailto:<?php echo htmlspecialchars($admin->getUser()->getEmail()); ?>"><?php echo htmlspecialchars($admin->getUser()->getEmail()); ?></a></td>
    <td><?php echo $admin->getUser()->getLink(); ?></td>
    <td><?php echo $admin->getCharity()->getLink(); ?></td>
    <td><?php echo $admin->getApprovedBy()->getLink(); ?></td>
    <td><?php echo $admin->getApprovedAt()->format('Y-m-d H:i:s'); ?></td>
</tr>
<?php endforeach; ?>