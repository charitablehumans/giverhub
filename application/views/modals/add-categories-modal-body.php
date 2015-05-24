<?php
/** @var \Base_Controller $CI */
$CI =& get_instance();
/** @var array $body_data */
/** @var \Entity\User $user */
$user = $body_data['user'];
/** @var boolean $my_dashboard */
$my_dashboard = $body_data['my_dashboard'];
?>
<p class="lead txtCntr">Select Causes</p>
<div class="row">
    <?php foreach($user->getCategoriesForSelecting() as $category): ?>
        <?php
        /** @var boolean $selected */
        $selected = $category['selected'];
        /** @var \Entity\CharityCategory $category */
        $category = $category['category'];
        ?>
        <div class="col-md-6 btn-group">
            <button
                title="<?php echo htmlspecialchars($category->getName()); ?>"
                data-charity-category-id="<?php echo $category->getId(); ?>"
                type="button"
                class="btn btn-primary btn-add-categories-category <?php echo $selected ? 'active' : ''; ?> col-md-10"
                data-toggle="dropdown"
                ><?php echo htmlspecialchars($category->getName()); ?></button>
            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                <span class="caret"></span>
                <span class="sr-only">Toggle Dropdown</span>
            </button>
            <ul class="cause-dropdown dropdown-menu" role="menu">
                <?php foreach($user->getCausesForSelecting($category) as $cause): ?>
                    <?php
                    /** @var boolean $selected */
                    $selected = $cause['selected'];
                    /** @var \Entity\CharityCause $cause */
                    $cause = $cause['cause'];
                    ?>
                    <li><a
                            title="<?php echo htmlspecialchars($cause->getName()); ?>"
                            data-charity-cause-id="<?php echo $cause->getId(); ?>"
                            <?php if($my_dashboard): ?>
                                data-toggle="button"
                            <?php endif; ?>
                            class="btn btn-primary btn-add-categories-cause <?php echo $selected ? 'active' : ''; ?>"
                            type="button"
                            href="#"><?php echo htmlspecialchars($cause->getName()); ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endforeach; ?>
</div>