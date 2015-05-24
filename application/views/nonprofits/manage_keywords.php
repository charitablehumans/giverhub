<?php
/** @var \Entity\Charity $charity */
/** @var \Charity $CI */
$CI =& get_instance();
?>

<?php $this->load->view('/nonprofits/_charity_header', array('charity' => $charity)); ?>

<main class="petition_main" role="main">
    <section class="container">
        <div class="row" id="manage-keyword-div">

            <?php $this->load->view('/nonprofits/_nonprofit_new_nav'); ?>

            <div class="col-md-10 col-sm-9 clearfix">
                <h2 class="page_title">Manage Keywords</h2>

                <button class="btn btn-primary btn-xs btn-select-all-keywords">Select All</button>
                <?php foreach($charity->getKeywords() as $keyword): ?>
                    <button type="button" data-keyword-id="<?php echo $keyword->getId(); ?>" class="btn btn-primary btn-sm btn-keyword btn-keyword-<?php echo $keyword->getId(); ?>" data-toggle="button"><?php echo htmlspecialchars($keyword->getKeywordName()); ?></button>
                <?php endforeach; ?>

                <button class="btn btn-danger btn-delete-selected-keywords">DELETE SELECTED</button>
            </div>
            <br/>
        </div><!-- row end -->
    </section><!-- container end -->
</main>