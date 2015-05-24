<?php /** @var \Entity\GiverCoin[] $givercoin */ ?>
<section role="main">
    <div class="row">
        <form method="post">
            <table>
                <tr>
                    <th>ID</th>
                    <th>Event</th>
                    <th>Amount</th>
                    <th>Description</th>
                </tr>
                <?php foreach($givercoin as $gc): ?>
                    <tr>
                        <td><?php echo $gc->getId(); ?></td>
                        <td><?php echo htmlspecialchars($gc->getEvent()); ?></td>
                        <td><input name="amount[<?php echo $gc->getId(); ?>]" type="number" min="0" step="0.01" value="<?php echo round($gc->getAmount(),2); ?>"></td>
                        <td><textarea name="description[<?php echo $gc->getId(); ?>]"><?php echo htmlspecialchars($gc->getDescription()); ?></textarea></td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <input id="submit" type="submit">
            <input type="reset">
        </form>
    </div>
</section>
<script type="text/javascript">
    jQuery(document).ready(function() {
        jQuery('#submit').click(function() {
            return confirm('Are you sure?');
        });
    });
</script>