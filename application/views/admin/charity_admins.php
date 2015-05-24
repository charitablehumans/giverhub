<?php
/** @var \Entity\CharityAdmin[] $admins */
/** @var \Base_Controller $CI */
$CI =& get_instance();
?>
<style>
    #charity_admins_table {
        table-layout: fixed;
    }
    #charity_admins_table td {
        overflow: auto;
    }
    .row {
        max-width: 90%;
    }
</style>
<div class="row">
    <h1>Charity Admins</h1>
</div>

<div class="row">
    <table border="0" cellpadding="0" cellspacing="0" width="100%" id="charity_admins_table">
        <thead>
        <tr>
            <th scope="col">Email</th>
            <th scope="col">Name</th>
            <th scope="col">Nonprofit</th>
            <th scope="col">Approved By</th>
            <th scope="col">Approved Date</th>
        </tr>
        </thead>
        <tbody>
            <?php $CI->load->view('/admin/_charity_admins', array('admins' => $admins)); ?>
        </tbody>
    </table>
</div>
