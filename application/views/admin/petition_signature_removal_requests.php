<?php
/** @var \Entity\PetitionSignatureRemovalRequest[] $requests */
/** @var \Base_Controller $CI */
$CI =& get_instance();
?><style>
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
    <h1>Removal Requests</h1>
</div>

<div class="row">
    <table border="0" cellpadding="0" cellspacing="0" width="100%" id="charity_admins_table">
        <thead>
        <tr>
            <th scope="col">User</th>
            <th scope="col">Email</th>
            <th scope="col">Type</th>
            <th scope="col">Granted</th>
            <th scope="col">Details</th>
        </tr>
        </thead>
        <tbody>
            <?php foreach($requests as $request): ?>
                <tr>
                    <td><?php echo $request->getUser()->getLink(); ?></td>
                    <td><a href="mailto:<?php echo $request->getUser()->getEmail(); ?>"><?php echo $request->getUser()->getEmail(); ?></a></td>
                    <td><?php echo $request->getType(); ?></td>
                    <td><?php echo $request->getDateRemoved() ? 'GRANTED' : 'NOT GRANTED YET'; ?></td>
                    <td><a href="/admin/petition_signature_removal_request/<?php echo $request->getId(); ?>">details</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
