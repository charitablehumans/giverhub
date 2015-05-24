<?php
/** @var \Base_Controller $CI */
$CI =& get_instance();
?>

<?php $this->load->view('/members/_header', array('user' => $CI->user)); ?>

<main class="members_main my_petitions_page" role="main" id="main_content_area">

    <section class="container">
        <div class="row">
            <?php $this->load->view('/members/_member_new_nav', array('user' => $CI->user)); ?>

			<div class="col-md-10">
                <div class="row">
                    <div class="col-md-6 my-petition-divider">
                        <h2 class="page_title_members">Your Petitions</h2>
                        <?php foreach($CI->user->getMyPetitions() as $petition): ?>
                            <div class="g-petition-block">
                                <div class="col-md-12"><span class="g-petition-block-target"><?php echo htmlspecialchars($petition->getTargetText()); ?>: </span><?php echo htmlspecialchars($petition->getWhatText()); ?><br/></div>
                                <div class="col-md-12 g-petition-block-signature"><span>Signatures: <?php echo $petition->getGiverhubPetitionSignaturesCount(); ?></span><span style="float:right"><a href="<?php echo $petition->getUrl(); ?>">View</a> | <a href="#">Delete</a></span></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="col-md-6">
                        <h2 class="page_title_members">Petitions You Signed</h2>


                        <?php foreach($CI->user->getMySignedGiverhubPetitions() as $signature): ?>
                            <?php $signed_petition = $signature->getPetition(); ?>
                            <div class="g-petition-block">
                                <div class="col-md-12">
                                    <span class="g-signed-petitoin-target"><?php echo htmlspecialchars($signed_petition->getTargetText()); ?> : </span><?php echo htmlspecialchars($signed_petition->getWhatText());?><br/>
                                </div>
                                <div class="col-md-12 g-petition-block-signature">
                                    <span>Signatures: <?php echo $signed_petition->getGiverhubPetitionSignaturesCount(); ?></span>
                                    <?php if (!$signature->getIsHide()) : ?>
                                        <span class="g-spetition-hide-signature">Hide Signature?</span>
                                    <?php else: ?>
                                        <span class="g-spetition-hidden-signature">Signature Hidden</span>
                                    <?php endif; ?>

                                </div>
                            </div>
                        <?php endforeach; ?>

                        <?php foreach($CI->user->getMySignedChangeOrgPetitions() as $signature): ?>
                            <?php $changeOrgPetition = $signature->getPetition(); ?>
                            <div class="g-petition-block">
                                <div class="col-md-12">
                                    <span class="g-signed-petitoin-target"><?php echo htmlspecialchars($changeOrgPetition->getFirstTargetString()); ?> : </span><?php echo htmlspecialchars($changeOrgPetition->getTitle());?><br/>
                                </div>
                                <div class="col-md-12 g-petition-block-signature">
                                    <span>Signatures: <?php echo $changeOrgPetition->getSignaturesCount(); ?></span><span style="float: right; font-size: 12px">Change.org Petition</span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div><!-- row end -->
    </section><!-- container end -->
</main>
