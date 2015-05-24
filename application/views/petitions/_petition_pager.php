<?php
    /** @var integer $current_page */
    /** @var \Entity\ChangeOrgPetition $petition */
    /** @var string $tabName */
    /** @var integer $pages */
    $PAGER_OPTIONS = 7;
?>
<div class="btn-toolbar">
    <div class="btn-group pager petition_pager">

        <?php if($current_page == 1): ?>
            <span class="btn btn-default">&lt;&lt;</span>
        <?php else: ?>
            <a class="btn btn-default" href="/petitions/<?php echo $petition->getGiverhubUrlSlug(); ?>/<?php echo $tabName; ?>/1<?php echo isset($_GET['from']) ? '?from='.$_GET['from'] : null; ?>">&lt;&lt;</a>
        <?php endif; ?>


        <?php if($current_page > 1): ?>
            <a class="btn btn-default" href="/petitions/<?php echo $petition->getGiverhubUrlSlug(); ?>/<?php echo $tabName; ?>/<?php echo $current_page-1; ?><?php echo isset($_GET['from']) ? '?from='.$_GET['from'] : null; ?>">&lt;</a>
        <?php endif; ?>

        <?php
            if ($pages < $PAGER_OPTIONS) {
                $start = 1;
                $end = $pages;
            } else {
                $start = $current_page - floor($PAGER_OPTIONS/2);
                if ($start < 1) {
                    $start = 1;
                }
                $end = $start + $PAGER_OPTIONS-1;
                if ($end > $pages) {
                    $start -= ($end - $pages);
                    $end = $pages;
                }
            }
        ?>

        <?php $i = 0; ?>
        <?php for($page = $start; $page <= $end; $page++): ?>

            <?php if ($i > 4): ?>
                <?php $extra_class = 'hide-on-mobile'; ?>
            <?php else: ?>
                <?php $extra_class = ''; ?>
            <?php endif; ?>

            <?php if($current_page == $page): ?>
                <span class="btn btn-default <?php echo $extra_class; ?>"><?php echo $page; ?></span>
            <?php else: ?>
                <a class="btn btn-default <?php echo $extra_class; ?>" href="/petitions/<?php echo $petition->getGiverhubUrlSlug(); ?>/<?php echo $tabName; ?>/<?php echo $page; ?><?php echo isset($_GET['from']) ? '?from='.$_GET['from'] : null; ?>"><?php echo $page; ?></a>
            <?php endif; ?>
            <?php $i++; ?>
        <?php endfor; ?>

        <?php if($current_page < $pages): ?>
            <a class="btn btn-default" href="/petitions/<?php echo $petition->getGiverhubUrlSlug(); ?>/<?php echo $tabName; ?>/<?php echo $current_page+1; ?><?php echo isset($_GET['from']) ? '?from='.$_GET['from'] : null; ?>">&gt;</a>
        <?php endif; ?>

        <?php if($current_page == $pages): ?>
            <span class="btn btn-default">&gt;&gt;</span>
        <?php else: ?>
            <a class="btn btn-default" href="/petitions/<?php echo $petition->getGiverhubUrlSlug(); ?>/<?php echo $tabName; ?>/<?php echo $pages; ?><?php echo isset($_GET['from']) ? '?from='.$_GET['from'] : null; ?>">&gt;&gt;</a>
        <?php endif; ?>


    </div>
</div>