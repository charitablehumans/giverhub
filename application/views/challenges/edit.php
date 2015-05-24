<?php
/** @var \Entity\Challenge $challenge */
/** @var \Base_Controller $this */
?>
<main role="main" class="challenge-main" id="challenge-edit-main">
    <section class="container">
        <div class="row">
            <?php $this->load->view('/members/_member_new_nav', array('user' => $this->user)); ?>

            <div class="col-md-10 col-sm-9">
                <div class="row">
                    <div class="col-xs-12">
                        <?php $this->load->view('/challenges/_impact'); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="block">
                            <?php $this->load->view('/challenges/_form', ['challenge' => $challenge]); ?>
                        </div>
                    </div>
                    <div class="col-sm-6 challenge-preview-container">
                        <div class="block challenge-preview-block">
                            <header><span class="preview">Challenge Preview</span>Challenge Preview</header>
                            <p class="preview-displayed-here">Preview will be displayed here.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>