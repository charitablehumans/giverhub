<?php
/** @var \Entity\FAQ[] $faqs */
?>
<section class="gh_secondary_header clearfix">
    <section class="container empty_header">
        <div class="row">
            <h2 class="col-md-6">FAQs <small class="blk">Frequently Asked Questions</small></h2>

            <div class="col-md-6">
                <div class="text-right">
                    <img alt="FAQ" src="../assets/images/faq_header.png" style="height:125px; margin-top:-10px;">
                </div>
            </div>
        </div><!-- row end -->
    </section><!-- empty_header end -->
</section>

<main class="" role="main" id="main_content_area">

    <section class="filter clearfix">
        <section class="container">
            <div class="row">

                <div class="col-md-8">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <div class="navbar-collapse display-none">
                        <span class="title">Filter:</span>

                        <ul id="faq-nav-filters" class="nav nav-pills">
                            <li><a href="#" data-filter="donation">DONATIONS</a></li>
                            <li><a href="#" data-filter="charity">CHARITIES</a></li>
                            <li><a href="#" data-filter="giverhub">GIVER HUB</a></li>
                            <li><a href="#" data-filter="all" class="active" >ALL</a></li>
                        </ul>
                    </div>
                </div>

            </div><!-- row end -->
        </section><!-- container end -->
    </section>

    <section class="container">
        <!-- Code added to display FAQ headings at top of the page -->
        <div class="row">
            <div class="col-md-12 faq-content-wrapper">
                <?php $i=1; foreach ($faqs as $faq): ?>
                    <div class="faq">
                        <span class="faq_index"><?php echo $i ?>.</span>
                        <div class="faq_content" data-filter="<?php echo $faq->getFqFilter(); ?>">
                            <h3><a href="#<?php echo htmlspecialchars($faq->getFqQues());?>"><?php echo htmlspecialchars($faq->getFqQues()); ?></a></h3>
                        </div><!-- faq_content end -->
                    </div><!-- faq end -->
                    <?php $i++; endforeach; ?>
            </div><!-- col-md-12 end -->
        </div>
        <div class="row">

            <div class="col-md-12 faq-content-wrapper">

                <?php $i=1; foreach ($faqs as $faq): ?>
                    <div class="block faq" id="<?php echo htmlspecialchars($faq->getFqQues()); ?>">
                        <span class="faq_index"><?php echo $i ?>.</span>

                        <div class="faq_content" data-filter="<?php echo $faq->getFqFilter(); ?>">
                            <h3><?php echo htmlspecialchars($faq->getFqQues()); ?></h3>
                            <p><?php echo $faq->getFqAns(); ?></p>
                        </div><!-- faq_content end -->
                    </div><!-- faq end -->
                <?php $i++; endforeach; ?>

            </div><!-- col-md-12 end -->

        </div><!-- row end -->
    </section><!-- container end -->
</main>

