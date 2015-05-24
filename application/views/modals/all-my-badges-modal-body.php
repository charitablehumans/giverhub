<?php
/** @var array $body_data */
/** @var \Entity\Badge[] $badges */
$badges = $body_data['badges'];
?>
<p class="lead txtCntr">All your badges</p>
<ul class="user_badges">
    <?php foreach($badges as $badge): ?>
        <li>
            <a class="badget<?php echo $badge->getCategoryId(); ?> ttip"
               data-html='true'
               data-content='
                <div class="progress progress-sm">
                    <div class="progress-bar"
                        role="progressbar"
                        aria-valuenow="<?php echo max(1,min($badge->getPoints()/1000, 100)); ?>"
                        aria-valuemin="0"
                        aria-valuemax="100"
                        style="width:<?php echo max(1,min($badge->getPoints()/1000,100)); ?>%;"></div>
                </div>'
               data-trigger='hover'
               data-placement='bottom'></a>
        </li>
    <?php endforeach; ?>
</ul>