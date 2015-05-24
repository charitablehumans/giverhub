<?php
/** @var \Entity\CharityAdminRequest[] $requests */
/** @var \Base_Controller $CI */
$CI =& get_instance();
?>
<?php foreach($requests as $request): ?>
<tr>
    <td><a href="mailto:<?php echo htmlspecialchars($request->getUser()->getEmail()); ?>"><?php echo htmlspecialchars($request->getUser()->getEmail()); ?></a></td>
    <td><?php echo $request->getUser()->getLink(); ?></td>
    <td><?php echo htmlspecialchars($request->getMessage()); ?></td>
    <td>
        <?php foreach($request->getPictures() as $picture): ?>
            <a href="<?php echo htmlspecialchars($picture->getUrl()); ?>"><img src="<?php echo htmlspecialchars($picture->getThumbUrl()); ?>"></a>
        <?php endforeach; ?>
    </td>
    <td><?php echo $request->getCharity()->getLink(); ?></td>
    <td><a href="#" data-request-id="<?php echo $request->getId(); ?>" class="approve-charity-admin-request button">Approve</a></td>
</tr>
<?php endforeach; ?>