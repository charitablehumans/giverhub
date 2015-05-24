<section class="gh_secondary_header clearfix">

    <section class="container empty_header">

        <div class="row">
            <h2 class="col-md-6">
				Contact<br> Us


                <small class="blk"></small>

            </h2>


            <div class="col-md-6">



                <div class="text-right">
                    <img alt="Contact" src="../assets/images/contact_header.png" style="height:155px; margin-top:-10px;">
                </div>



            </div>
        </div><!-- row end -->

    </section><!-- empty_header end -->
</section>
<main class="" role="main" id="main_content_area">
    <section class="filter clearfix">
        <section class="container">




            <span class="big color_light" style="color:#B9B9B9; font-size:18px; font-weight:600;">We are looking forward to hearing from you.</span>


        </section><!-- container end -->
    </section><!-- filter end -->


    <section class="container">
        <div class="row">

            <form action="#" class="gh_find_friens col-md-10 col-md-offset-1 gh_spacer_42" method="post" >

                <?php if (@$contact_success): ?>
                    <div class="alert alert-success">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <?php echo $contact_success; ?>
                </div>

                <?php elseif (@$contact_error): ?>
                        <div class="alert alert-danger">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <h4>Sorry, but there was a problem processing the contact form.</h4>
                    <?php echo $contact_error; ?>
                    </div>

                <?php if (@$contact_error2): ?>
                            <div class="alert alert-danger">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <?php echo $contact_error2; ?>
                        </div>
                <?php endif; ?>
                <?php endif; ?>

                            <small class="color_light text-center blk gh_spacer_28">Please fill out the contact form below and hit submit. We will get back to you shortly.</small>

                            <div class="clearfix">

                                <!-- LEFT -->
                                <div class="col-md-5">
                                    <div class="form-group clearfix">
                                        <input type="text" class="form-control" placeholder="Your Name" id="contactName" name="ContactForm[name]" value="<?php echo isset($ContactForm['name']) ? htmlspecialchars($ContactForm['name']) : '' ?>">
                                    </div><!-- form-group end -->

                                    <div class="form-group clearfix">
                                        <input type="text" class="form-control" placeholder="Your Email" id="contactEmail" name="ContactForm[email]" value="<?php echo isset($ContactForm['email']) ? htmlspecialchars($ContactForm['email']) : '' ?>">
                                    </div><!-- form-group end -->

                                    <div class="form-group clearfix">
                                        <input type="text" class="form-control" placeholder="Subject" id="contactSubject" name="ContactForm[subject]" value="<?php echo isset($ContactForm['subject']) ? htmlspecialchars($ContactForm['subject']) : '' ?>">
                                    </div><!-- form-group end -->
                                </div>

                                <!-- RIGHT -->
                                <div class="col-md-7">
                                    <div class="form-group clearfix">
                                        <textarea class="form-control" style="height:152px !important; resize:none;" id="contactMessage" name="ContactForm[message]"><?php echo isset($ContactForm['message']) ? htmlspecialchars($ContactForm['message']) : '' ?></textarea>
                        </div><!-- form-group end -->
                    </div>

                </div><!-- clearfix end -->

                <div class="form-group col-md-12 clearfix">
                    <button type="submit" class="btn btn-primary noise"><span>SUBMIT</span></button>
                </div>

            </form><!-- gh_find_friens end -->

        </div><!-- row end -->
    </section><!-- container end -->
</main>