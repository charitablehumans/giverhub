<?php
/** @var \Base_Controller $CI */
$CI =& get_instance();
/** @var \Entity\ActivityFeedPost $post */
?>
<?php $GLOBALS['super_timers']['iiii1'] = microtime(true) - $GLOBALS['super_start']; ?>

<section class="gh_secondary_header clearfix">
    <section class="container empty_header"></section><!-- empty_header end -->
</section>

<main class="" role="main">

    <section class="container post-page-main-container">
        <div class="row">
            <div class="col-md-6">
                <div class="block post-block">
                    <table class="table table-hover post-table">
                        <tbody>
                            <?php $this->load->view('/members/_activity', array('activity' => $post, 'context' => $CI->user && $CI->user->getId() == $post->getFromUser()->getId() ? 'my' : 'other')); ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-md-6">
                <?php $this->load->view('/partials/_fun_donations', ['my_dashboard' => true, 'user' => $CI->user]); ?>

                <div class="load-nonprofit-feed block"><img class="cntr" alt="Loading Nonprofit Feed" src="/images/ajax-loaders/ajax-loader.gif"></div>
                <div class="load-petition-feed block"><img class="cntr" alt="Loading Petition Feed" src="/images/ajax-loaders/ajax-loader.gif"></div>
            </div>
        </div>
    </section>
</main>

<?php $this->load->view('bets/_modals'); ?>