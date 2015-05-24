<?php
/** @var \Base_Controller $CI */
$CI =& get_instance();
/** @var \Entity\Challenge $challenge */
?>
<main class="challenge-page-main challenge-main" id="challenge-main-index" role="main">
    <section class="filter clearfix title-outer">
        <section class="container title-inner">
            <h1 class="vegur_light"><?php echo htmlspecialchars($challenge->getNameWithChallenge()); ?></h1>
        </section>
    </section>

    <section class="container">
        <div class="row">
            <div class="col-md-6">
                <?php $CI->load->view('/challenges/_info.php', ['challenge' => $challenge]); ?>
            </div>
            <div class="col-md-6">
                <?php $this->load->view('/partials/_fun_donations', ['my_dashboard' => 1, 'user' => $CI->user]); ?>

                <div class="load-nonprofit-feed block"><img class="cntr" src="/images/ajax-loaders/ajax-loader.gif"></div>
                <div class="load-petition-feed block"><img class="cntr" src="/images/ajax-loaders/ajax-loader.gif"></div>

                <?php if (!$CI->user): ?>
                    <?php if (isset($_GET['fb-challenge'])): ?>
                        <div class="trigger-fb-sign-in hide"></div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>
</main>
<?php if ($CI->user) { $CI->load->view('partials/_donation_modals'); }