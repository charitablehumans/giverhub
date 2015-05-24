<?php
/** @var \Entity\Mission[] $missions */
/** @var \Base_Controller $CI */
$CI =& get_instance();
?>
<?php foreach($missions as $mission): ?>
    <div class="block mission-block">
        <div class="header">
            <div class="submitted-by-container pull-left">
                <?php $CI->load->view('/nonprofits/_submitted_by_user', ['user' => $mission->getUser()]); ?>
            </div>
            <?php if ($CI->user): ?>
                <div class="vote-container pull-left">
                    <?php $CI->load->view('/nonprofits/_mission_vote', ['mission' => $mission]); ?>
                </div>
                <?php if ($mission->getUser() == $CI->user): ?>
                    <button
                        data-loading-text="DELETING"
                        type="button"
                        data-mission-id="<?php echo $mission->getId(); ?>"
                        class="btn btn-danger btn-delete-mission pull-right">DELETE</button>
                <?php endif; ?>
            <?php endif; ?>
        </div>
        <div class="mission">
            <?php echo $mission->getMission(); ?>
        </div>
        <div class="source">
            Source: <?php echo $mission->getSourceFormatted(); ?>
        </div>
    </div>
<?php endforeach; ?>