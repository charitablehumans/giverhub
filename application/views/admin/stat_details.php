<?php
/** @var $stat_name string */
/** @var $stat_names array */
/** @var \Entity\Stat[] $stats */
?>
<div class="row">
    <select id="stat_select">
        <option>--SELECT--</option>
        <?php foreach($stat_names as $name): ?>
            <option
                <?php if ($name == $stat_name): ?>selected="selected"<?php endif; ?>
                value="<?php echo htmlspecialchars($name); ?>"><?php echo htmlspecialchars($name); ?></option>
        <?php endforeach; ?>
    </select>
    <?php if ($stats): ?>
        <table class="tablesorter">
            <thead>
                <tr>
                    <th>When</th>
                    <th>Who</th>
                    <th>Joined</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($stats as $stat): ?>
                    <tr>
                        <td><?php echo $stat->getTimestamp()->format('Y-m-d H:i:s'); ?></td>
                        <td><?php echo $stat->getExtra('user.link'); ?></td>
                        <td><?php echo $stat->getExtra('user.joined'); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
<style>
    /* tables */
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

        $(document).on('change', '#stat_select', function() {
            window.location = '/admin/stat_details/'+jQuery('#stat_select').val();
        });
    });
</script>