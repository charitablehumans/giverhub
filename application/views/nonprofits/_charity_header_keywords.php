<?php
/** @var \Entity\Charity $charity */
/** @var \Charity $CI */
$CI =& get_instance();
?>
<?php foreach($charity->getKeywords() as $keyword): ?>
    <li
        class="charity-header-keyword"
        data-keyword-id="<?php echo $keyword->getId(); ?>"
        data-keyword-up-votes="<?php echo $keyword->getUpVotes(); ?>"
        data-keyword-down-votes="<?php echo $keyword->getDownVotes(); ?>"
        data-keyword-flagged="<?php echo $keyword->getFlagged(); ?>"
        ><?php echo strtolower(htmlspecialchars($keyword->getKeywordName())); ?></li>
<?php endforeach; ?>