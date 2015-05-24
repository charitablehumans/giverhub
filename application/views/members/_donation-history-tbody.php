<?php
/** @var \Base_Controller $this */
/** @var array $simplifiedArray */
?>
<?php foreach($simplifiedArray as $donationYears): ?>
    <tr class="charity_name success">
        <td class="total-label"><h4 class="user_name color_title">Yearly Total</h4></td>
        <td class="total-val" colspan="5"><h4 class="user_name color_title"><?php echo CURRENCY_SIGN.$donationYears['total']; ?></h4></td>
    </tr>
    <?php foreach($donationYears as $key=>$value): ?>
        <?php if (is_array($value)): ?>
            <?php foreach($value as $monthDetails): ?>
                <tr class="charity_name warning">
                    <td class="total-label"><h4 class="user_name color_title">Monthly Total</h4></td>
                    <td class="total-val" colspan="5"><h4 class="user_name color_title"><?php echo CURRENCY_SIGN.$monthDetails['total']; ?></h4></td>
                </tr>
                <?php foreach($monthDetails['details'] as $monthEntries): ?>
                    <?php foreach($monthEntries as $monthEntry): ?>
                        <tr>
                            <td class="name"><?php echo $monthEntry['charity_name']; ?></td>
                            <td class="amount"><?php echo CURRENCY_SIGN.$monthEntry['amount']; ?></td>
                            <td class="dated"><?php echo $monthEntry['date']; ?></td>
                            <td class="time"><?php echo $monthEntry['time']; ?></td>
                            <td class="cause" colspan="2"><?php echo htmlspecialchars($monthEntry['causes']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            <?php endforeach; ?>
        <?php endif; ?>
    <?php endforeach; ?>
<?php endforeach; ?>
