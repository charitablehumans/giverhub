<?php
/** @var \Entity\Challenge[] $challenges */
/** @var \Base_Controller $CI */
$CI =& get_instance();
?>
<table class="my-challenges-table">
    <?php foreach($challenges as $challenge): ?>
        <tr>
            <td class="name">
                <?php echo htmlspecialchars($challenge->getNameWithChallenge()); ?>
            </td>
            <td class="button">
                <?php if ($challenge->isDraft()): ?>
                    <a href="/challenge/edit/<?php echo $challenge->getId(); ?>"
                        class="btn btn-xs btn-success"
                        data-loading-text="Edit Draft">Edit Draft</a>
                <?php else: ?>
                    <a href="<?php echo $challenge->getUrl(); ?>" class="btn btn-xs btn-info" data-loading-text="Open">View</a>
                <?php endif; ?>
            </td>

        </tr>
    <?php endforeach; ?>
</table>