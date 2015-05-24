<?php
/** @var \Admin $CI */
$CI =& get_instance();
/** @var \Entity\User[] $rows */
?>
<div class="row">
    <div class="column large-12">
        <table class="tablesorter" style="width:100%;">
            <thead>
            <tr>
                <th>email</th>
                <th>last online</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($rows as $user): ?>
                <tr>
                    <td><?php echo $user->getEmail(); ?></td>
                    <td><?php echo $user->getLastOnline(); ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
