<?php
/** @var \Base_Controller $CI */
$CI =& get_instance();
?>
<?php if ($this->user): ?>
    <?php $this->load->view('/members/_header', array('user' => $CI->user)); ?>
<?php else: ?>
    <section class="gh_secondary_header clearfix">

        <section class="container empty_header">

            <div class="row">
                <h2>
                    Bet-A-Friend
                </h2>
            </div><!-- row end -->

        </section><!-- empty_header end -->
    </section>
<?php endif; ?>

    <main class="members_main" role="main">

        <section class="container">
            <div class="row">

                <?php if ($this->user): ?>
                    <?php $this->load->view('/members/_member_new_nav', array('user' => $CI->user)); ?>
                <?php endif; ?>

                <div class="<?php if ($this->user): ?>col-md-5 col-sm-4<?php else: ?>col-md-4 col-sm-3<?php endif; ?>">
                    <div class="block bet-form-on-bet-a-friend-page-block">
                        <?php $this->load->view('/bets/_bet_form'); ?>
                        <footer>
                            <a href="#" class="pull-left btn-make-bet-save-for-later">Save this bet for later</a> <a href="#" class="btn btn-warning btn-make-bet-review">REVIEW</a>
                        </footer>
                    </div>
                </div>


                <div class="<?php if ($this->user): ?>col-md-5 col-sm-5<?php else: ?>col-md-4 col-sm-4<?php endif; ?>">
                    <div class="block bet-a-friend-info-block">
                        <h2>Bet-A-Friend lets you donate OTHER people´s money to YOUR favorite nonprofits!</h2>
                        <p>OK, maybe not quite, but if you win that´s precisely what happens! You set the terms of the bet, pick your friend, pick your nonprofit, and if you win they make the agreed upon donation to the nonprofit that you chose. Of course, if they win you have to donate to their nonprofit. Fair is fair.</p>
                        <h3>What if there´s a disagreement?</h3>
                        <p>We urge bettors to make their terms as unambiguous as possible so as to limit the potential for a disagreement. Ideally the terms should have only two possible outcomes, one for each side of the bet, but we don´t force anyone to make the donation. The loser can always choose to violate the terms of the agreement and refuse to make the donation.</p>
                        <h3>Examples</h3>
                        <p>The winner of a sporting event, the winner of an election, whether something is true or false, etc.</p>
                    </div>
                </div>

                <?php if (!$this->user): ?>
                    <div class="col-md-4 col-sm-5">
                        <?php $this->load->view('/members/_nonprofit_feed', ['user'=>null,'preHtml' => '/petitions/_nonprofit_feed_pre_html']); ?>

                        <?php $this->load->view('/petitions/_welcome_video_block'); ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>

    </main>

<?php if ($this->user): ?>
    <?php $this->load->view('/partials/_donation_modals'); ?>
    <?php $this->load->view('/bets/_modals'); ?>
<?php endif; ?>