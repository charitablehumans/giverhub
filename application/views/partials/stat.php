<?php
    /** @var \Base_Controller $this */
    /** @var string $name */
    if (!isset($name) || !is_string($name)) {
        throw new Exception('name needs to be a string');
    }
?>
<?php if ($this->user && $this->user->isAdmin()): ?>
    <p class="admin-stats">
        Unique: <span><?php echo \Entity\Stat::getUnique($name); ?></span><br/>
        Total: <span><?php echo \Entity\Stat::getTotal($name); ?></span><br/>
        <a href="/admin/stat_details/<?php echo $name; ?>">details</a>
    </p>
<?php endif; ?>