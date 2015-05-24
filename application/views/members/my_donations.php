<?php
/** @var \Entity\User $user */
/** @var array $simplifiedArray */
/** @var \Base_Controller $CI */
$CI =& get_instance();
?>
<?php $this->load->view('/members/_header', array('user' => $user)); ?>

<main class="members_main" role="main" id="main_content_area">

    <section class="container">
        <div class="row">

            <!--<div class="col-md-12">
                <h2 class="page_title">MY DONATIONS</h2>
            </div>-->

            <!-- Include new left side bar menu -->
            <?php //$this->load->view('/members/_member_new_nav', array('user' => $user)); ?>

            <div class="col-md-12">

                <div class="block what-is-donation-history-block">
                    <header>What is your <span class="underline">Donation History</span>?</header>
                    <section>
                        <img src="/img/view-all-icon.png" alt="View all of your past GiverHub donations in one convenient location">
                        <span>View all of your past GiverHub donations in one convenient location</span>
                    </section>
                    <section>
                        <img src="/img/file-add-icon.png" alt="Use the Add Donation button to add any donations made outside of GiverHub and make tax day a breeze">
                        <span>Use the <button type="button" class="btn btn-primary btn-xs btn-add-outside-donation">Add Donation</button> button to add any donations made outside of GiverHub and make tax day a breeze</span>
                    </section>
                </div>

                <div class="block donation-history-block">

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th class="name">NONPROFIT NAME</th>
                                <th class="amount">AMOUNT</th>
                                <th class="dated">DATE</th>
                                <th class="time">TIME</th>
                                <th class="cause">CAUSE</th>
                                <th class="add-donation add-donation-th"><button type="button" class="btn btn-primary btn-sm btn-add-outside-donation">Add Donation</button></th>
                            </tr>
                            </thead>
                            <tbody class="donation-history-tbody">
                                <?php $this->load->view('/members/_donation-history-tbody', ['simplifiedArray' => $simplifiedArray]); ?>
                            </tbody>
                        </table>
                    </div><!-- table-responsive end -->

                </div><!-- block end -->

            </div><!-- col-md-12 end -->

        </div><!-- row end -->
    </section><!-- container end -->

</main>

<?php
$CI->modal('add-outside-donation-modal', [
    'header' => 'Add Donation',
    'modal_size' => 'col-md-3',
    'body' => '<form action="#">
                    <input tabindex="1" class="form-control nonprofit" name="nonprofit" placeholder="Nonprofit Name (required)">
                    <input tabindex="2" class="form-control amount" name="amount" placeholder="Donation Amount (required)">
                    <input tabindex="3" class="form-control donation-date" name="date" placeholder="Donation Date (required)">
                    <input tabindex="4" class="form-control time" name="time" placeholder="Donation Time">
                    <input tabindex="5" class="form-control cause" name="cause" placeholder="Donation Cause">
                </form>',
    'body_string' => true,
    'footer' => '<button type="button" class="btn-submit-outside-donation btn btn-primary" data-loading-text="Add Donation" tabindex="6">Add Donation</button>',
    'footer_string' => true,
]);
