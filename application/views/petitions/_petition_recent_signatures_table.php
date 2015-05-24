<?php /** @var \Entity\ChangeOrgPetition $petition */ ?>
<table id="charity-updates-table" class="table table-hover">
    <tbody>
        <?php
            /** @var \Entity\ChangeOrgPetitionSignature[] $signatures */
            $signatures = \Entity\ChangeOrgPetitionSignature::findBy(array('petition_id' => $petition->getId()), array('signed_on' => 'desc'), 3);
        ?>
        <?php if (!$signatures): ?>
            <tr>
                <td>No Signatures Yet.</td>
            </tr>
        <?php else: ?>
            <?php foreach($signatures as $signature): ?>
                <tr>
                    <td class="col-md-6" style="border-top:none;">
                        <div class="color_title recent-signature-container">
                            <p><?php echo htmlspecialchars($signature->getName()); ?> <small class="color_light"><?php echo htmlspecialchars(strtoupper($signature->getCity().', '.$signature->getCountryCode())); ?></small></p>
                            <small class="text-right color_light blk"><?php echo $signature->getIntervalFromNow(); ?></small>
                            <a
                                href="#"
                                class="removal-request-button removal-request-button-<?php echo $signature->getId(); ?> gh_tooltip"
                                title="Click here if you want to make this signature anonymous"
                                data-placement="left"
                                data-type="signature"
                                data-id="<?php echo $signature->getId(); ?>"
                                type="button">Request Removal</a>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>