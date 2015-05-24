<?php
/** @var array $tasks */
/** @var \Base_Controller $CI */
$CI =& get_instance();
?>

<div class="row">
    <div class="column large-12">
        <?php if ($this->session->flashdata('message')): ?>
            <div data-alert class="alert-box success radius">
                <?php echo $this->session->flashdata('message'); ?>
                <a href="#" class="close">&times;</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="row">
    <div class="columns">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>COMMAND</th>
                    <th>CREATED</th>
                    <th>STARTED</th>
                    <th>STOPPED</th>
                    <th>STATUS</th>
                    <th>PID</th>
                    <th>OUTPUT</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($tasks as $task): ?>
                    <tr>
                        <td><?php echo $task['id']; ?></td>
                        <td><?php echo htmlspecialchars($task['command']); ?></td>
                        <td><?php echo $task['created_at']; ?></td>
                        <td><?php echo $task['started_at']; ?></td>
                        <td><?php echo $task['stopped_at']; ?></td>
                        <td><?php echo $task['status']; ?></td>
                        <td><?php echo $task['pid']; ?></td>
                        <td><a href="/admin/task_output/<?php echo $task['id']; ?>" class="button tiny">VIEW</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script type="text/javascript">
    try {
        jQuery(document).ready(function() {
            try {

            } catch(e) {
                window.adminError({e:e});
            }
        });
    } catch(e) {
        window.adminError({e:e});
    }
</script>