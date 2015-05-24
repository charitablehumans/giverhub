<?php
/** @var \Entity\Charity $charity */
/** @var \Base_Controller $CI */
$CI =& get_instance();
?>

<?php $this->load->view('/nonprofits/_charity_header', array('charity' => $charity)); ?>

<main
    data-charity-id="<?php echo $charity->getId(); ?>"
    data-is-charity-admin="<?php echo $CI->user && $CI->user->isCharityAdmin($charity) ? '1' : '0'; ?>"
    data-opportunities="<?php echo htmlspecialchars(json_encode($charity->getVolunteeringOpportunities())); ?>"
    data-time-zones="<?php echo htmlspecialchars(json_encode(\Entity\CharityVolunteeringOpportunity::$time_zones)); ?>"
    class="petition_main volunteering-info volunteering-page"
    role="main"
    id="main_content_area">
    <section class="container">
        <div class="row">

            <div class="hide-on-sm-md-resolution">
                <?php $this->load->view('/nonprofits/_nonprofit_new_nav'); ?>
            </div>

            <div class="col-lg-10 col-md-12 col-sm-12">
                <?php $this->load->view('/volunteering/_volunteering_list', ['two_col' => true]); ?>

                <?php $dt = new \DateTime(); ?>
                <?php for($x = 0; $x < 12; $x++): ?>

                    <div class="vol-cal-block block center">
                        <header><?php echo $dt->format('F Y'); ?> (<?php echo $charity->getNrOfEventsForMonth($dt); ?>)</header>
                        <?php if ($x != 0): ?>
                            <button type="button" class="btn btn-primary btn-show-calendar">Show calendar</button>
                        <?php endif; ?>
                        <div class="vol-cal <?php if ($x != 0): ?>hide<?php endif; ?>"
                             data-timezone="<?php echo htmlspecialchars(\Entity\CharityVolunteeringOpportunity::$php_time_zones['UTC-05:00']); ?>"
                             data-nonprofit-id="<?php echo $charity->getId(); ?>"
                             <?php if ($x != 0): ?>
                                data-default-date="<?php echo $dt->format('Y-m-d'); ?>"
                             <?php endif; ?>
                            data-cal-height="700"
                             data-events="<?php echo htmlspecialchars(json_encode($charity->getVolunteeringOpportunities())); ?>"></div>
                    </div>
                    <?php $dt->modify('first day of next month'); ?>
                <?php endfor; ?>
                <?php
                $CI->modal('vol-event-modal', [
                    'header' => 'Volunteering Event',
                    'body' => '<div class="wrapper"></div>',
                    'body_string' => true,
                    'footer' => '',
                    'modal_size' => 'col-md-3',
                ]);
                ?>
                <?php $this->load->view('/volunteering/_message_modal', ['charity' => $charity]); ?>

                    <!--<div class="col-md-6">
                        <?php //$this->load->view('/volunteering/_volunteering_list_add', ['charity' => $charity]); ?>
                        <?php //$this->load->view('/volunteering/_volunteering_review_block', ['charity' => $charity]); ?>
                    </div>-->
            </div>
        </div>
    </section>
</main>