<?php
/** @var \Entity\Charity $charity */
/** @var Base_Controller $CI */
$CI =& get_instance();

$data = $charity->getCitizenData();
?>

<?php foreach($data as $group): ?>
        <h4><?php echo $group['label']; ?></h4>
        <table class="table table-striped">
            <tbody>
            <?php foreach($group['fields'] as $field => $value): ?>
                <tr>
                    <td><?php echo htmlspecialchars($field); ?></td>
                    <td><?php echo htmlspecialchars($value); ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
<?php endforeach; ?>