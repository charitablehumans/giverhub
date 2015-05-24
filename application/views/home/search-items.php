<?php
/** @var \Entity\BaseEntity[] $items */
use \Entity\Charity;
use \Entity\ChangeOrgPetition;
?>
<?php if ($items): ?>
<div class="row">

    <?php
    $col1 = $col2 = $col3 = $col4 = '<div class="col-md-3 col-sm-6">';

    $current_col = 1;

    foreach ($items as $item) {
        if ($item instanceof Charity) {
            $itemHtml = $this->load->view('/nonprofits/charity-item', array('charity' => $item), true);
        } elseif ($item instanceof ChangeOrgPetition) {
            $itemHtml = $this->load->view('/petitions/petition-item', array('petition' => $item), true);
        } else {
            throw new Exception('Unknown item type. ' . get_class($item));
        }
        if ($current_col == 1) {
            $col1 .= $itemHtml;
        } elseif ($current_col == 2) {
            $col2 .= $itemHtml;
        } elseif ($current_col == 3) {
            $col3 .= $itemHtml;
        } elseif ($current_col == 4) {
            $col4 .= $itemHtml;
        }

        $current_col++;

        if ($current_col == 5) {
            $current_col = 1;
        }
    }

    echo($col1 . '</div>');
    echo($col2 . '</div>');
    echo($col3 . '</div>');
    echo($col4 . '</div>');

    ?>
</div>
<?php endif; ?>