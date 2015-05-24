<?php
/** @var integer $activity_id */
/** @var string $activity_type */
?>
<div class="delete-activity-post">
    <button
        type="button"
        data-activity-id="<?php echo $activity_id; ?>"
        data-activity-type="<?php echo $activity_type; ?>"
        data-loading-text="hide"
        data-container="body"
        title="Hiding an activity simply removes the notification from your own feed while leaving the activity intact in others feeds."
        class="gh_tooltip btn btn-xs btn-primary btn-hide-activity">hide</button>
</div>
