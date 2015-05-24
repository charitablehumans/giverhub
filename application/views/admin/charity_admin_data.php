<?php
/** @var \Entity\CharityAdminData[] $datas */
/** @var \Base_Controller $CI */
$CI =& get_instance();
?>
<style>
    #charity_admin_data_table {
        table-layout: fixed;
    }
    #charity_admin_data_table td {
        overflow: auto;
    }
    .row {
        max-width: 90%;
    }
</style>
<div class="row">
    <h1>Charity Admin Data</h1>
</div>

<div class="row">
    <table border="0" cellpadding="0" cellspacing="0" width="100%" id="charity_admin_data_table">
        <thead>
        <tr>
            <th scope="col">User</th>
            <th scope="col">Email</th>
            <th scope="col">Nonprofit</th>
            <th scope="col">Field</th>
            <th scope="col">Value</th>
            <th scope="col">Created</th>
            <th scope="col">Updated</th>
        </tr>
        </thead>
        <tbody>
            <?php foreach($datas as $data): ?>
                <tr>
                    <td><?php echo $data->getUser()->getLink(); ?></td>
                    <td><a href="mailto:<?php echo $data->getUser()->getEmail(); ?>"><?php echo $data->getUser()->getEmail(); ?></a></td>
                    <td><?php echo $data->getCharity()->getLink(); ?></td>
                    <td><?php echo $data->getField(); ?></td>
                    <td><?php echo htmlspecialchars($data->getValue()); ?></td>
                    <td><?php echo $data->getDateCreated()->format('Y-m-d H:i:s'); ?></td>
                    <td><?php echo $data->getDateUpdated()->format('Y-m-d H:i:s'); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
