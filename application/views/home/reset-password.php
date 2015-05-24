<?php
/** @var string $token */

if (!isset($current_page_tab) || $current_page_tab == '') {
    $current_page_tab = 'rating';
}
?>
<section class="gh_secondary_header empty clearfix">

    <section class="container empty_header">

        <div class="row">
            <h2 class="col-md-6">
                Reset Password


                <small class="blk"></small>

            </h2>


            <div class="col-md-6">





                <div class="text-right">
                    <img alt="Reset Password" src="../assets/images/faq_header.png" style="height:125px; margin-top:-10px;">
                </div>

            </div>
        </div><!-- row end -->

    </section><!-- empty_header end -->
</section>

<main class="" role="main">

    <section class="container">
        <div class="row">

            <div class="col-md-3 faq-content-wrapper">
                <br/>
                <div class="form-group">
                    <input type="password" class="form-control" id="reset_password_1" placeholder="new password">
                </div>

                <div class="">
                    <input type="password" class="form-control" id="reset_password_2" placeholder="repeat new password">
                </div>
                <br/>
                <a data-token="<?php echo $token; ?>" class="btn btn-primary btn-submit-reset-password">SUBMIT</a>

            </div><!-- col-md-12 end -->

        </div><!-- row end -->
    </section><!-- container end -->

</main>

