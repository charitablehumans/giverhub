<?php
/** @var \Entity\ChangeOrgPetition $petition */
/** @var integer $current_page */

/** @var \Petitions $CI */
$CI =& get_instance();
$user = \Base_Controller::$staticUser;
?>

<?php $this->load->view('/giverhub-petitions/_header', array('petition' => $petition)); ?>

<main class="petition_main" role="main">




<section class="container">
<div class="row">

    <?php $this->load->view('/giverhub-petitions/_nav', array('petition' => $petition)); ?>

    <!--<div class="col-md-10">
        <h2 class="page_title">SIGNATURES</h2>
    </div>-->

    <div class="col-md-10 col-sm-9">
    <?php
        $itemsPerPage = 10;
        $count = $petition->getGiverhubPetitionSignaturesCount();
        $pages = ceil($count / $itemsPerPage);
    ?>
    <?php if ($count): ?>
        <?php if ($pages > 1): ?>
            <?php $CI->load->view('giverhub-petitions/_pager', array(
                                                          'pages' => $pages,
                                                          'current_page' => $current_page,
                                                          'petition' => $petition,
                                                          'tabName' => 'signatures'
                                                     )); ?>
        <?php endif; ?>

            <div class="block petition_block">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr class="bold">
                                <th class="col-md-3">Name</th>
                                <th class="col-md-2">Date Signed</th>
                                <th class="col-md-2">City</th>
                                <th class="col-md-2">State</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach($petition->getGiverhubPetitionSignatures($current_page, $itemsPerPage) as $signature): 
								$userAddress 	= $signature->getUser()->getDefaultAddress();
								$cityDetails 	= \Entity\CharityCity::findOneBy(['id' => $userAddress->getCityId()]);
								$stateDetails 	= \Entity\CharityState::findOneBy(['id' => $userAddress->getStateId()]);
						?>
                            <tr>
                                <td><?php echo htmlspecialchars($signature->getUser()->getName()); ?></td>
                                <td><?php echo $signature->getSignedOnDt()->format('m/d/Y'); ?></td>
                                <td><?php echo htmlspecialchars($cityDetails->getName()); ?></td>
                                <td><?php echo htmlspecialchars($stateDetails->getName()); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div><!-- table-responsive end -->
            </div>


        <?php if ($pages > 1): ?>
            <?php $CI->load->view('giverhub-petitions/_pager', array(
                                                          'pages' => $pages,
                                                          'current_page' => $current_page,
                                                          'petition' => $petition,
                                                          'tabName' => 'signatures'
                                                     )); ?>
        <?php endif; ?>
    <?php else: ?>


            <div class="block user_info">
                Sorry, No Signatures Yet!
            </div><!-- block end -->


    <?php endif; ?>

    </div>
</div>
<!-- row end -->
</section>
<!-- container end -->
</main>

<?php if ($CI->user) { $CI->load->view('partials/_address-modal'); }
