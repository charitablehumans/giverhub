<?php
/** @var \Base_Controller $this */
?>
<?php $this->load->view('/members/_header', array('user' => $this->user)); ?>

<main class="members_main" role="main" id="main_content_area">

    <section class="container">
        <div class="row">

            <?php //$this->load->view('/members/_member_new_nav', array('user' => $this->user)); ?>

            <div class="col-md-12">

                <div class="block petition-history-block">
                    <header>
                        <div class="col-sm-5">Petition Name</div>
                        <div class="col-sm-2">Date Signed</div>
                        <div class="col-sm-2">Signature</div>
                        <div class="col-sm-3"></div>
                    </header>
                    <?php foreach($this->user->getMySignedChangeOrgPetitions() as $signature): ?>
                        <div class="row">
                            <div class="col-sm-5 petition-name"><span class="m-label">Petition Name </span><?php echo $signature->getPetition()->getLink(); ?></div>
                            <div class="col-sm-2 date-signed"><span class="m-label">Date Signed </span><?php echo $signature->getSignedAtDt()->format('m/d/y'); ?></div>
                            <div class="col-sm-2 signature"><span class="m-label">Signature </span><?php echo $signature->isHidden() ? 'Hidden' : 'Public'; ?></div>
                            <div class="col-sm-3 share"><span class="m-label"></span>
                                <button type="button"
                                        data-href="<?php echo $signature->getPetition()->getGiverhubUrl(); ?>"
                                        data-petition-id="<?php echo $signature->getPetition()->getId(); ?>"
                                        class="btn btn-share-petition-history btn-primary">Share</button></div>
                        </div>
                    <?php endforeach; ?>
                </div><!-- block end -->

            </div><!-- col-md-12 end -->

        </div><!-- row end -->
    </section><!-- container end -->

</main>