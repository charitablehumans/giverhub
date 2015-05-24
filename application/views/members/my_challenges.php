<?php
/** @var \Entity\Challenge[] $challenges */
/** @var \Base_Controller $CI */
$CI =& get_instance();
?>

<?php $this->load->view('/members/_header', array('user' => $CI->user)); ?>

<main class="members_main my_petitions_page" role="main" id="main_content_area">

    <section class="container">
        <div class="row">
            <?php //$this->load->view('/members/_member_new_nav', array('user' => $CI->user)); ?>

            <div class="col-md-7 block">
                <h2>My Challenges</h2>
                <?php if (!$challenges): ?>
                    <p>You have not created any challenges yet.</p>
                <?php else: ?>
                    <?php $CI->load->view('/challenges/_my_challenges_table', ['challenges' => $challenges]); ?>
                <?php endif; ?>
            </div>
            <div class="col-md-5">
                <?php $this->load->view('/partials/_fun_donations', ['my_dashboard' => 1, 'user' => $CI->user]); ?>
            </div>
        </div><!-- row end -->
    </section><!-- container end -->

</main>
