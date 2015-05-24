<div class="row">
    <table class="tablesorter">
        <thead>
            <tr>
                <th>ID</th>
                <th>Speed</th>
                <th>Url</th>
                <th>User</th>
                <th>Data</th>
                <th>Date</th>
            </tr>
        </thead>
        <?php foreach(\Entity\SiteSpeed::findBy([], ['date' => 'desc']) as $site_speed): ?>
            <?php /** @var \Entity\SiteSpeed $site_speed */ ?>
            <tr>
                <td><?php echo $site_speed->getId(); ?></td>
                <td><?php echo $site_speed->getSpeed(); ?></td>
                <td><a
                        title="<?php echo htmlspecialchars($site_speed->getUrl()); ?>"
                        href="<?php echo htmlspecialchars($site_speed->getUrl()); ?>"
                        class="url"><?php echo $site_speed->getUrl(); ?></a></td>
                <td><?php echo $site_speed->getUserId(); ?></td>
                <td><div class="data"><?php echo nl2br(print_r(json_decode($site_speed->getExtra(), true), true)); ?></div></td>
                <td><?php echo $site_speed->getDate()->format('Y-m-d H:i:s'); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
<style>
    .data {
        max-height: 50px;
        overflow: auto;
    }
    .url {
        max-width: 400px;
        overflow: hidden;
        text-overflow: ellipsis;
        display: block;
    }
    table.tablesorter {
        font-family:arial;
        background-color: #CDCDCD;
        margin:10px 0pt 15px;
        font-size: 8pt;
        width: 100%;
        text-align: left;
    }
    table.tablesorter thead tr th, table.tablesorter tfoot tr th {
        background-color: #e6EEEE;
        border: 1px solid #FFF;
        font-size: 8pt;
        padding: 4px;
    }
    table.tablesorter thead tr .header {
        background-image: url(bg.gif);
        background-repeat: no-repeat;
        background-position: center right;
        cursor: pointer;
    }
    table.tablesorter tbody td {
        color: #3D3D3D;
        padding: 4px;
        background-color: #FFF;
        vertical-align: top;
    }
    table.tablesorter tbody tr.odd td {
        background-color:#F0F0F6;
    }
    table.tablesorter thead tr .headerSortUp {
        background-image: url(/img/asc.gif);
    }
    table.tablesorter thead tr .headerSortDown {
        background-image: url(/img/desc.gif);
    }
    table.tablesorter thead tr .headerSortDown, table.tablesorter thead tr .headerSortUp {
        background-color: #8dbdd8;
    }
</style>
<script src="/assets/scripts/jquery.tablesorter.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $(".tablesorter").tablesorter();
    });
</script>