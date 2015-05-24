<?php /** @var \Entity\GivingPot $giving_pot */ ?>
<?php if ($giving_pot->getCardOnFile()): ?>
    <div class="selected-card">
        <?php echo $giving_pot->getCardOnFile(); ?>
    </div>
<?php endif; ?>
<button type="button"
        class="btn btn-success btn-giving-pot-payment-method">Select Credit Card</button>
<div class="payment-method-error alert alert-danger hide" role="alert">
    <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
    <span class="sr-only">Error:</span>
    You must select a payment method first.
</div>