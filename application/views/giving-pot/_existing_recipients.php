<?php
/** @var \Entity\GivingPot $giving_pot */
?>
<?php if ($giving_pot->getRecipients()): ?>
<div class="block">
    <header>Recipients</header>
    <table>
        <thead>
            <tr>
                <th class="name">Name</th>
                <th class="email">Email</th>
                <th class="amount">Amount</th>
                <th class="remaining">Remaining</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($giving_pot->getRecipients() as $recipient): ?>
                <tr>
                    <td class="name"><?php echo htmlspecialchars($recipient->getName()); ?></td>
                    <td class="email"><?php echo htmlspecialchars($recipient->getEmail()); ?></td>
                    <td class="amount">$<?php echo htmlspecialchars($recipient->getGivercard()->getAmount()); ?></td>
                    <td class="remaining">$<?php echo htmlspecialchars($recipient->getGivercard()->getBalanceAmount()); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>