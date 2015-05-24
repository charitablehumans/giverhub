<?php
/** @var string $letter */
/** @var \Entity\SitemapPages[] $sitemap_pages */
/** @var \Entity\ChangeOrgPetition[]|\Entity\Charity[] $entities */
/** @var integer $page */
/** @var integer $pages */
/** @var string $url_prefix */
/** @var string $type */
?>
<section class="gh_secondary_header clearfix">

    <section class="container empty_header">

        <div class="row">
            <h2>
                A-Z Sitemap - <?php echo ucfirst($type); ?> - <?php echo strtoupper($letter); ?>
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
                    <li><a href="<?php echo $url_prefix; ?>"><?php echo ucfirst($type); ?></a></li>
                    <li><a href="<?php echo $url_prefix . $letter; ?>"><?php echo strtoupper($letter); ?></a></li>
                    <li class="active"><?php echo $page; ?></li>
                </ol>
                <div class="block">
                <?php $GLOBALS['super_timers']['azl1'] = microtime(true) - $GLOBALS['super_start']; ?>
                <?php $chunks = array_chunk($entities, 20); ?>
                <?php foreach($chunks as $entities): ?>
                    <div class="col-md-3 a-z-links">
                        <?php foreach($entities as $entity): ?>
                            <?php /** @var \Entity\Charity[]|\Entity\ChangeOrgPetition */ ?>
                            <?php echo $entity->getLink(); ?>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
                <?php $GLOBALS['super_timers']['azl2'] = microtime(true) - $GLOBALS['super_start']; ?>
                </div>
            </div>
        </div>
        <?php if ($pages > 1): ?>
            <div class="row">
                <div class="block">
                    <center>
                        <?php if ($page != 1): ?>
                            <a href="<?php echo $url_prefix . strtolower($letter); ?>/1">First</a>
                        <?php endif; ?>

                        <?php if ($page > 1): ?>
                            <a href="<?php echo $url_prefix . strtolower($letter); ?>/<?php echo $page - 1; ?>">Prev</a>
                        <?php endif; ?>

                        <?php
                            $start = max($page - 5, 1);
                            $end = min($start + 10, $pages);
                        ?>
                        <?php for ($i = $start; $i <= $end; $i++): ?>
                            <?php if ($page == $i): ?>
                               <?php echo $i; ?>
                            <?php else: ?>
                                <a href="<?php echo $url_prefix . strtolower($letter); ?>/<?php echo $i; ?>"><?php echo $i; ?></a>
                            <?php endif; ?>
                        <?php endfor; ?>

                        <?php if ($page < $pages): ?>
                            <a href="<?php echo $url_prefix . strtolower($letter); ?>/<?php echo $page + 1; ?>">Next</a>
                        <?php endif; ?>
                        <?php if ($page != $pages): ?>
                            <a href="<?php echo $url_prefix . strtolower($letter); ?>/<?php echo $pages; ?>">Last</a>
                        <?php endif; ?>
                    </center>
                </div>
            </div>
        <?php endif; ?>
    </section>
</main>