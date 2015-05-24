<?php
/** @var \Base_Controller $CI */

/** @var array $growth_rate */

$CI =& get_instance();
?>

<div class="row">
    <h1>Users Graph</h1>
</div>

<div class="row">
    <img src="/admin/users_graph_img" alt="Users Graph">
</div>

<div class="row">
    <img src="/admin/growth_rate_img" alt="Users Graph">
</div>

<div class="row">
    <img src="/admin/monthly_growth_rate_img" alt="Users Graph">
</div>

<div class="row">
    <table style="min-width: 60%;">
        <tr>
            <th>One week ago</th>
            <td><?php echo $growth_rate['value_week_ago']; ?></td>
        </tr>
        <tr>
            <th>Now</th>
            <td><?php echo $growth_rate['value_now']; ?></td>
        </tr>
        <tr>
            <th>Growth Rate</th>
            <td><?php echo $growth_rate['growth_rate']; ?>%</td>
        </tr>
        <tr>
            <th># of users that joined since first removal request</th>
            <td><?php echo $growth_rate['joined_since_first_removal_request']; ?></td>
        </tr>
        <tr>
            <th># of users that joined and made one or more removal requests</th>
            <td><?php echo $growth_rate['users_that_joined_and_requested_removal']; ?></td>
        </tr>
        <tr>
            <th>% of users that joined and made one or more removal requests since the date of the first removal request</th>
            <td><?php echo $growth_rate['percentage_of_users_that_joined_and_requested_removal']; ?>%</td>
        </tr>
    </table>
</div>

<div class="row">
    <table style="min-width: 60%;">
        <caption>Social info for users.. Each group shows the total count for the group and how big the group is in percentage compared to the total number of users.<br/>
        This EXCLUDES admins and users that have not confirmed their email (manually or by signing in using google or facebook)<br/>
        The groups overlap each other.. for example, the group type "facebook" can also be found in "facebook and google"..
        </caption>
        <thead>
        <tr>
            <th>Group</th>
            <th>Count</th>
            <th>Percentage</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach(\Entity\User::getSocialPercentages() as $row): ?>
            <tr>
                <th><?php echo $row['type']; ?></th>
                <td><?php echo $row['cnt']; ?></td>
                <td><?php echo round($row['pcnt'],1); ?>%</td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div class="row">
    <table style="min-width: 60%;">
        <caption>Different classes of users.. <strong>confirmed</strong> means that they have confirmed their email (manually or by using google/facebook).<br/>
            <strong>registered</strong> means that they signed up manually but never confirmed their email. They are INCLUDED in the graphs above!!</caption>
        <thead>
            <tr>
                <th>Type</th>
                <th>Count</th>
                <th>Percentage</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach(\Entity\User::getCapabilitiesPercentages() as $row): ?>
                <tr>
                    <th><?php echo $row['capabilities']; ?></th>
                    <td><?php echo $row['cnt']; ?></td>
                    <td><?php echo round($row['pcnt'],1); ?>%</td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>