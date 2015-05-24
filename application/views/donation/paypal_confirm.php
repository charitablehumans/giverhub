<?php
/** @var array $response */
/** @var \Entity\Donation $donation */
?>
<section class="gh_secondary_header clearfix">

    <section class="container empty_header">

        <div class="row">
            <h2 class="col-md-6">
                PayPal Confirm
                <small class="blk"></small>
            </h2>

        </div><!-- row end -->

    </section><!-- empty_header end -->
</section>
<main class="" role="main">
    <section class="filter clearfix">
        <section class="container">
            <h1 class="vegur_light">
                <?php if ($response['success']): ?>
                    <strong>Congratulations!</strong> Your donation to <?php echo $donation->getCharity()->getLink(); ?> is now confirmed!
                <?php else: ?>
                    Your donation to <?php echo $donation->getCharity()->getLink(); ?> was not confirmed. There was an unexpected error.
                <?php endif; ?>
            </h1>
        </section>
    </section>
</main>

<?php if (GIVERHUB_LIVE): ?>
    <div class="trigger-ga-event hide" data-url="/virtual/donation/success/paypal"></div>
<?php endif; ?>