<?php /** @var \Entity\CharityCause $cause */ ?>
<tr>
    <td colspan="4">
        <div class="row collapse">
            <div class="small-11 columns">
                <input class="no-bottom-margin add-keyword-input" type="text" placeholder="New Keyword">
            </div>
            <div class="small-1 columns">
                <a href="#" class="no-bottom-margin button postfix btn-add-keyword" data-loading-text="Adding..." data-cause-id="<?php echo $cause->getId(); ?>">Add</a>
            </div>
        </div>
    </td>
</tr>
<?php foreach($cause->getKeywords() as $keyword): ?>
    <tr>
        <th width="10%;">Keyword</th>
        <td><?php echo htmlspecialchars($keyword->getKeyword()); ?></td>
        <td width="10%"><label for="keyword_strength_<?php echo $keyword->getId(); ?>"><span class="saving">Strong:</span> <input class="keyword-strength-checkbox" id="keyword_strength_<?php echo $keyword->getId(); ?>" type="checkbox" data-keyword-id="<?php echo $keyword->getId(); ?>" <?php if ($keyword->getStrength()): ?>checked="checked"<?php endif; ?>></label></td>
        <td width="10%"><a class="button tiny alert btn-delete-keyword" data-loading-text="DELETING..." data-keyword-id="<?php echo $keyword->getId(); ?>" href="#">DELETE!</a></td>
    </tr>
<?php endforeach; ?>