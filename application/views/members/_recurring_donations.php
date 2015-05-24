<?php
/** @var \Entity\RecurringDonation[] $recurring_donations */
/** @var \Base_Controller $CI */
$CI =& get_instance();
?>
<?php if (!$recurring_donations): ?>
    You have no history of recurring donations.
<?php else: ?>
    <table class="table">
        <thead>
            <tr>
                <th>CHARITY</th>
                <th>AMOUNT</th>
                <th>INTERVAL</th>
                <th>LAST DATE</th>
                <th>NEXT DATE</th>
                <th>CANCEL</th>
                <th class="gh_tooltip"
                    data-toggle="tooltip"
                    data-placement="top"
                    data-container="body"
                    title="Check the box to receive an email notification 24 hours before the recurring donation is scheduled to be made">NOTIFY</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($recurring_donations as $recurring_donation): ?>
            <tr class="<?php echo $recurring_donation->getStatus(); ?>" id="recurring-donation-tr-<?php echo $recurring_donation->getRecurringDonationId(); ?>">
                <td><?php echo htmlspecialchars($recurring_donation->getNpoName()); ?></a></td>
                <td>$<?php echo round($recurring_donation->getAmount(), 2); ?></td>
                <td><?php echo $recurring_donation->getRecurType(); ?></td>
                <td><?php echo (new \DateTime($recurring_donation->getLastDate()))->format('Y-m-d'); ?></td>
                <td class="next-date">
                    <?php if($recurring_donation->getStatus() == 'Live'): ?>
                        <?php echo (new \DateTime($recurring_donation->getNextDate()))->format('Y-m-d'); ?>
                    <?php else: ?>
                        <?php echo htmlspecialchars($recurring_donation->getStatus()); ?>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if ($recurring_donation->getStatus() == 'Live'): ?>
                    <button
                        class="btn btn-xs btn-danger btn-cancel-recurring-donation"
                        data-recurring-donation-id="<?php echo $recurring_donation->getId(); ?>"
                        type="button">CANCEL</button>
                    <?php else: ?>
                        <?php echo htmlspecialchars($recurring_donation->getStatus()); ?>
                    <?php endif; ?>
                </td>
                <?php if ($recurring_donation->getStatus() == 'Live'): ?>
                    <td class="gh_tooltip"
                        data-toggle="tooltip"
                        data-placement="top"
                        data-container="body"
                        title="Check the box to receive an email notification 24 hours before the recurring donation is scheduled to be made">
                        <input <?php if ($recurring_donation->getNotify()): ?>checked="checked"<?php endif; ?>
                               type="checkbox"
                               value=""
                               class="recurring-donation-notify-checkbox"
                               data-recurring-donation-id="<?php echo $recurring_donation->getId(); ?>"/>
                        <img alt="Loading" src="/images/ajax-loaders/ajax-loader2.gif" style="display:none;">
                    </td>
                <?php else: ?>
                    <td>-</td>
                <?php endif; ?>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>