<?php
    /** @var integer $current_page */
    /** @var \Entity\Petition $petition */
    /** @var string $tabName */
    /** @var integer $pages */
?>
<div class="btn-toolbar">
    <div class="btn-group pager petition_pager">

        <?php if($current_page == 1): ?>
            <span class="btn btn-default">FIRST</span>
        <?php else: ?>
            <a class="btn btn-default" href="<?php echo $petition->getUrl(); ?>/<?php echo $tabName; ?>/1<?php echo isset($_GET['from']) ? '?from='.$_GET['from'] : null; ?>">FIRST</a>
        <?php endif; ?>


        <?php if($current_page > 1): ?>
            <a class="btn btn-default" href="<?php echo $petition->getUrl(); ?>/<?php echo $tabName; ?>/<?php echo $current_page-1; ?><?php echo isset($_GET['from']) ? '?from='.$_GET['from'] : null; ?>">PREV</a>
        <?php endif; ?>

        <?php
            if ($pages < 10) {
                $start = 1;
                $end = $pages;
            } else {
                $start = $current_page - 5;
                if ($start < 1) {
                    $start = 1;
                }
                $end = $start + 9;
                if ($end > $pages) {
                    $start -= ($end - $pages);
                    $end = $pages;
                }
            }
        ?>

        <?php for($page = $start; $page <= $end; $page++): ?>
            <?php if($current_page == $page): ?>
                <span class="btn btn-default"><?php echo $page; ?></span>
            <?php else: ?>
                <a class="btn btn-default" href="<?php echo $petition->getUrl(); ?>/<?php echo $tabName; ?>/<?php echo $page; ?><?php echo isset($_GET['from']) ? '?from='.$_GET['from'] : null; ?>"><?php echo $page; ?></a>
            <?php endif; ?>
        <?php endfor; ?>

        <?php if($current_page < $pages): ?>
            <a class="btn btn-default" href="<?php echo $petition->getUrl(); ?>/<?php echo $tabName; ?>/<?php echo $current_page+1; ?><?php echo isset($_GET['from']) ? '?from='.$_GET['from'] : null; ?>">NEXT</a>
        <?php endif; ?>

        <?php if($current_page == $pages): ?>
            <span class="btn btn-default">LAST</span>
        <?php else: ?>
            <a class="btn btn-default" href="<?php echo $petition->getUrl(); ?>/<?php echo $tabName; ?>/<?php echo $pages; ?><?php echo isset($_GET['from']) ? '?from='.$_GET['from'] : null; ?>">LAST</a>
        <?php endif; ?>


    </div>
</div>
