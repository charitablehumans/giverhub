<?php /** @var \Entity\User[] $users */ ?>
<?php foreach($users as $user):?>
    <tr class="table_row_class" id="tr<?php echo $user->getId(); ?>">
        <td scope="col"><input data-user-id="<?php echo $user->getId(); ?>" class="checkers" type="checkbox"></td>
        <td scope="col">
            <a href="<?php echo base_url();?>admin/user_profile/<?php echo $user->getId(); ?>">
                <?php echo htmlspecialchars($user->getUsername()); ?>
            </a>
        </td>
        <td scope="col">
            <?php echo htmlspecialchars($user->getFname()); ?> <?php echo htmlspecialchars($user->getLname()); ?>
        </td>
        <td scope="col"><a href="<?php echo $user->getEmail(); ?>"><?php echo $user->getEmail(); ?></a></td>
        <td scope="col">
            <img height="40" width="40" src="<?php echo $user->getImageUrl(); ?>" class="avatar"/>
        </td>
        <td scope="col"><?php echo date("n/j/Y", strtotime($user->getJoined())); ?></td>
        <td scope="col"><?php echo $user->getCapabilities(); ?></td>
        <td scope="col">
            <input
                class="auto_follow_checkbox"
                data-user-id="<?php echo $user->getId(); ?>"
                type="checkbox"
                <?php if ($user->getAutoFollow()): ?>checked="checked"<?php endif; ?>
            >
        </td>
        <td scope="col">
            <button type="button" data-user-id="<?php echo $user->getId(); ?>" data-email="<?php echo $user->getEmail(); ?>" class="delete-button button tiny alert">DELETE</button>
        </td>
    </tr>
<?php endforeach; ?>