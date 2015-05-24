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

    <?php $this->load->view('/giverhub-petitions/_nav'); ?>

    <!--<div class="col-md-10">
        <h2 class="page_title">REASONS</h2>
    </div>-->

    <div class="col-md-10 col-sm-9">
    <?php
        $itemsPerPage = 10;
        $count = $petition->getGiverhubPetitionReasonCount();
        $pages = ceil($count / $itemsPerPage);
    ?>
    <?php if ($count): ?>

        <?php if ($pages > 1): ?>
            <?php $CI->load->view('giverhub-petitions/_pager', array(
                                                          'pages' => $pages,
                                                          'current_page' => $current_page,
                                                          'petition' => $petition,
                                                          'tabName' => 'reasons'
                                                     )); ?>
        <?php endif; ?>

            <div class="block petition_block">

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr class="bold">
                                <th class="col-md-2">Name</th>
                                <th class="col-md-4">Reason</th>
                                <th class="col-md-1">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach($petition->getGiverhubPetitionReasons($current_page, $itemsPerPage) as $reason): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($reason->getUser()->getName()); ?></td>
                                <td><i><?php echo htmlspecialchars($reason->getReason()); ?></i></td>
                                <td><?php echo $reason->getSignedOnDt()->format('m/d/Y'); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div><!-- table-responsive end -->

            </div><!-- block end -->

        <?php if ($pages > 1): ?>
            <?php $CI->load->view('giverhub-petitions/_pager', array(
                                                          'pages' => $pages,
                                                          'current_page' => $current_page,
                                                          'petition' => $petition,
                                                          'tabName' => 'reasons'
                                                     )); ?>
        <?php endif; ?>
    <?php else: ?>

            <div class="block user_info">
                Sorry, No Reasons Yet!
            </div><!-- block end -->

    <?php endif; ?>

    </div>
</div>
<!-- row end -->
</section>
<!-- container end -->
</main>

<?php if ($CI->user) { $CI->load->view('partials/_address-modal'); }
