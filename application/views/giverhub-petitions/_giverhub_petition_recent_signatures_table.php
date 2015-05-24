<?php /** @var \Entity\ChangeOrgPetition $petition */ ?>
<table id="charity-updates-table" class="table table-hover">
    <tbody>
        <?php
            /** @var \Entity\ChangeOrgPetitionSignature[] $signatures */
            $signatures = \Entity\PetitionSignature::findBy(array('petition_id' => $giverhubPetition->getId(), 'is_hide' => 0), array('signed_on' => 'desc'), 3);
        ?>
        <?php if (!$signatures): ?>
            <tr>
                <td>No Signatures Yet.</td>
            </tr>
        <?php else: ?>
            <?php foreach($signatures as $signature): 
				
				$userAddress = $signature->getUser()->getDefaultAddress();
				$cityDetails = \Entity\CharityCity::findOneBy(['id' => $userAddress->getCityId()]);
	?>
                <tr>
                    <td class="col-md-6" style="border-top:none;">
                        <div class="color_title">
                            <p><?php echo htmlspecialchars($signature->getUser()->getName()); ?> <small class="color_light"><?php echo htmlspecialchars(strtoupper($cityDetails->getName())); ?></small></p>
                            <small class="text-right color_light blk"><?php echo $signature->getIntervalFromNow(); ?></small>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>
