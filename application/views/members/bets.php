<?php
/** @var \Base_Controller $CI */
$CI =& get_instance();
?>
<?php $this->load->view('/members/_header', array('user' => $CI->user)); ?>

<main class="members_main bet-a-friend-page" role="main">

    <section class="container">
        <div class="row">

            <?php //$this->load->view('/members/_member_new_nav', array('user' => $CI->user)); ?>

            <div class="col-md-12">

                <div class="block">
                    <div class="bet-list-container bet-a-friend-page">
                        <?php $CI->load->view('bets/_bet-list', ['user' => $CI->user]); ?>
                    </div>
                </div><!-- block end -->

            </div><!-- col-md-12 end -->

        </div><!-- row end -->
    </section><!-- container end -->

</main>

<?php $CI->load->view('/partials/_donation_modals'); ?>
<?php $CI->load->view('/bets/_modals'); ?>