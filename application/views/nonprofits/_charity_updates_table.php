<?php /** @var \Entity\Charity $charity */ ?>
<table id="charity-updates-table" class="table table-hover">
    <tbody>
        <?php $charityUpdates = \Entity\CharityUpdate::findBy(array('charity_id' => $charity->getId()),array('date' => 'desc'),3); ?>
        <?php if (!$charityUpdates): ?>
            <tr>
                <td>No Updates Yet.</td>
            </tr>
        <?php else: ?>
            <?php foreach($charityUpdates as $charityUpdate): ?>
                <tr>
                    <td class="col-md-6" style="border-top:none;">
                        <div class="color_title">
                            <p><?php echo nl2br(htmlspecialchars($charityUpdate->getUpdate())); ?></p>
                            <small class="blk text-right color_light">by <?php echo htmlspecialchars(strtoupper($charityUpdate->getUser()->getName())); ?></small>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>