<?php
/** @var string $url_prefix */
/** @var \Entity\SitemapLetters[] $letters */
/** @var string $type */
?>
<section class="gh_secondary_header clearfix">

    <section class="container empty_header">

        <div class="row">
            <h2>
                A-Z Sitemap - <?php echo ucfirst($type); ?>
                <small class="blk"></small>
            </h2>

        </div><!-- row end -->

    </section><!-- empty_header end -->
</section>
<main class="" role="main">
    <section class="container">
        <div class="row">
            <div class="col-md-12">
                <br/>
                <ol class="breadcrumb">
                    <li><a href="/">Home</a></li>
                    <li><a href="/a-z">A-Z</a></li>
                    <li class="active"><?php echo ucfirst($type); ?></li>
                </ol>
                <center>
                    <?php foreach(array_chunk($letters, 10, true) as $letters): ?>
                        <div class="col-md-3">
                            <?php foreach($letters as $letter): ?>
                                <a href="<?php echo $url_prefix . strtolower($letter->getLetter()); ?>"><?php echo $letter->getLetter(); ?> <small>(<?php echo $letter->getCount(); ?>)</small></a><br/>
                            <?php endforeach; ?>
                        </div>
                    <?php endforeach; ?>
                </center>
            </div>
        </div>
    </section>
</main>