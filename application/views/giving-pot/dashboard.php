<?php
/** @var \Entity\GivingPot $giving_pot */
?>

<main role="main" class="giving-pot-main" id="giving-pot-dashboard-main">
    <section class="container">
        <div class="row">
            <div class="col-sm-6">
                <div class="block giving-pot-recipients-block" data-giving-pot-id="<?php echo $giving_pot->getId(); ?>">
                    <header>Recipients</header>
                    <form class="giving-pot-recipients-form">
                        <table>
                            <thead>
                                <tr>
                                    <th class="num">#</th>
                                    <th class="name">Name</th>
                                    <th class="email">Email</th>
                                    <th class="amount">Amount</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </form>
                    <footer>
                        <a href="#" class="btn-add-more-recipients">Add more recipients</a>
                        <button class="btn btn-primary btn-send-givercards"
                                data-loading-text="Send GiverCards"
                                type="button">Send GiverCards</button>
                    </footer>
                </div>
                <div class="giving-pot-existing-recipients-block">
                    <?php $this->load->view('/giving-pot/_existing_recipients', ['giving_pot' => $giving_pot]); ?>
                </div>
            </div>
            <div class="col-sm-6 giving-pot-preview-wrapper">
                <?php echo $giving_pot->render(); ?>
            </div>
        </div>
    </section>
</main>