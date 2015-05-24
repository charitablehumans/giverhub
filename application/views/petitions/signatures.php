<?php
/** @var \Entity\ChangeOrgPetition $petition */
/** @var integer $current_page */

/** @var \Petitions $CI */
$CI =& get_instance();
$user = \Base_Controller::$staticUser;
?>

<?php $this->load->view('/petitions/_petition_header', array('petition' => $petition)); ?>

<main class="petition_main change-org-petition-main" role="main">
    <section class="container">
        <div class="row">

            <?php $this->load->view('/petitions/_petition_new_nav'); ?>


            <div class="col-md-5 col-sm-4 petition-reason-signature-main-col">
                <?php $this->load->view('/petitions/_sign-block', ['petition' => $petition]); ?>

                <?php
                    $itemsPerPage = 20;
                    $count = $petition->getSignaturesCount();
                    $pages = ceil($count / $itemsPerPage);
                ?>
                <?php if ($count): ?>

                    <div class="block petition_block">
                        <div class="table-responsive">
                            <table class="table table-hover petition-signatures-table">
                                <tbody>
                                <?php foreach($petition->getSignatures($current_page, $itemsPerPage) as $signature): ?>
                                    <tr>
                                        <td>
                                            <?php echo htmlspecialchars($signature->getName()); ?><br/>
                                            <?php echo $signature->getSignedOnDt()->format('m/d/Y'); ?>
                                            <a
                                                href="#"
                                                class="removal-request-button removal-request-button-<?php echo $signature->getId(); ?> gh_tooltip"
                                                title="Click here if you want to make this signature anonymous"
                                                data-type="signature"
                                                data-placement="bottom"
                                                data-id="<?php echo $signature->getId(); ?>"
                                                type="button">Request Removal</a>
                                        </td>
                                        <td><?php if ($signature->getCity()): ?><?php echo htmlspecialchars($signature->getCity()); ?><br/><?php endif; ?>
                                            <?php if ($signature->getState()): ?><?php echo htmlspecialchars($signature->getState()); ?><br/><?php endif; ?>
                                            <?php echo htmlspecialchars($signature->getCountryName()); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div><!-- table-responsive end -->
                    </div>

                    <?php if ($pages > 1): ?>
                        <?php $CI->load->view('petitions/_petition_pager', array(
                                                                      'pages' => $pages,
                                                                      'current_page' => $current_page,
                                                                      'petition' => $petition,
                                                                      'tabName' => 'signatures'
                                                                 )); ?>
                    <?php endif; ?>
                <?php endif; ?>
            </div>

            <div class="col-md-5 col-sm-5">
                <?php $this->load->view('/partials/trending-petitions'); ?>

                <?php //$this->load->view('/members/_nonprofit_feed', ['user' => $user, 'preHtml' => '/petitions/_nonprofit_feed_pre_html', 'extra_classes' => ['petition-page-nonprofit-feed']]); ?>

                <?php $this->load->view('/petitions/_welcome_video_block'); ?>
            </div>
        </div>
        <!-- row end -->
    </section>
    <!-- container end -->
</main>

<?php
if ($CI->user) {
    $CI->load->view('partials/_address-modal');
    $CI->load->view('/petitions/_removal_request_modal');
}