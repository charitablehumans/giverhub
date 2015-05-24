<?php
/** @var \Base_Controller $CI */
$CI =& get_instance();
?>
<div class="table-responsive">
    <table class="table table-hover bet-a-friend-table">
        <thead>
        <tr>
            <th class="col-md-1">FRIEND</th>
            <th class="col-md-3">TERMS</th>
            <th class="col-md-1">AMOUNT</th>
            <th class="col-md-1 end-date">END DATE</th>
            <th class="col-md-3">NONPROFIT</th>
            <th class="col-md-3">STATUS</th>
        </tr>
        </thead>
        <tbody>
        <?php $bets = $CI->user->getBets(); ?>
        <?php if (!$bets): ?>
            <h3>You have no bets.</h3>
        <?php else: ?>
            <?php foreach($bets as $bet): ?>
                <?php if (!$bet->getFriends()): ?>
                    <tr class="bet-row bet-row-<?php echo $bet->getId(); ?>"
                    data-bet="<?php echo htmlspecialchars(json_encode($bet)); ?>">
                        <td>None.</td>
                        <td><?php echo htmlspecialchars($bet->getTerms(['remove_ending_dot' => true])); ?>.</td>
                        <td class="amount">$<?php echo $bet->getAmount(); ?></td>
                        <td class="end-date"><?php echo $bet->getDeterminationDate()->format('m/d/y'); ?></td>
                        <td>
                            <?php $my_nonprofit = $bet->getCharity(); ?>
                            <?php echo $my_nonprofit->getLink(); ?>
                        </td>
                        <td>
                            DRAFT<br/>
                            <button data-bet-id="<?php echo $bet->getId(); ?>" type="button" class="btn btn-edit-draft btn-sm btn-warning">EDIT</button>
                            <button data-bet-id="<?php echo $bet->getId(); ?>" type="button" class="btn btn-delete-draft btn-sm btn-danger">DELETE</button>
                        </td>
                    </tr>
                <?php endif; ?>
                <?php foreach($bet->getFriends() as $friend): ?>
                    <?php if ($bet->isToUser($CI->user) && $friend->getUser() != $CI->user): ?>
                        <?php continue; ?>
                    <?php endif; ?>
                    <?php $not_my_nonprofit = $bet->getUser() == $CI->user ? $friend->getCharity() : $bet->getCharity(); ?>
                    <?php $my_nonprofit = $bet->getUser() == $CI->user ? $bet->getCharity() : $friend->getCharity(); ?>
                    <?php $not_me = $bet->getUser() == $CI->user ? $friend->getFriend() : $bet->getUser(); ?>
                    <?php $my_claim = $bet->getMyClaim($CI->user); ?>
                    <?php $not_my_claim = $friend->getNotMyClaim($CI->user); ?>
                    <?php $my_donation = $friend->getMyDonation($CI->user); ?>
                    <?php $not_my_donation = $friend->getNotMyDonation($CI->user); ?>
                    <tr class="bet-row bet-row-<?php echo $bet->getId(); ?>"
                        data-bet="<?php echo htmlspecialchars(json_encode($bet)); ?>">

                        <td><?php echo $not_me->getLink(); ?></td>
                        <td><?php echo htmlspecialchars($bet->getTerms(['remove_ending_dot' => true])); ?>.</td>
                        <td class="amount">$<?php echo $bet->getAmount(); ?></td>
                        <td class="end-date"><?php echo $bet->getDeterminationDate()->format('m/d/y'); ?></td>
                        <td>
                            Mine: <?php echo ($my_nonprofit === null ? '-' : $my_nonprofit->getLink()); ?><br/><br/>
                            <?php if ($not_me instanceof \Entity\FacebookFriend): ?>
                                <?php echo htmlspecialchars($not_me->getFname()); ?>'s: <?php echo ($not_my_nonprofit === null ? '-' : $not_my_nonprofit->getLink()); ?>
                            <?php else: ?>
                                <a href="<?php echo $not_me->getUrl(); ?>"><?php echo htmlspecialchars($not_me->getFname()); ?>'s</a>: <?php echo ($not_my_nonprofit === null ? '-' : $not_my_nonprofit->getLink()); ?>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($bet->getStatus() == 'draft'): ?>
                                DRAFT<br/>
                                <button data-bet-id="<?php echo $bet->getId(); ?>" type="button" class="btn btn-edit-draft btn-sm btn-warning">EDIT</button>
                                <button data-bet-id="<?php echo $bet->getId(); ?>" type="button" class="btn btn-delete-draft btn-sm btn-danger">DELETE</button>
                            <?php elseif ($friend->getStatus() == 'rejected'): ?>
                                REJECTED BY <?php echo $friend->getFriend()->getLink(); ?><br/>
                                <a href="<?php echo $bet->getUrl(); ?>" type="button" class="btn btn-sm btn-primary">VIEW</a>
                            <?php elseif ($friend->getStatus() == 'accepted'): ?>
                                <?php if ($bet->isTimeForDetermination()): ?>
                                    <?php if ($my_claim === null): ?>
                                        <div>It's time to determine the winner of your bet!</div>
                                        <div class="bet-claim-row-cont">
                                            <button type="button" data-bet-id="<?php echo $bet->getId(); ?>" class="btn btn-sm btn-success btn-claim-win">I won! :)</button>
                                            <button type="button"
                                                    data-bet-id="<?php echo $bet->getId(); ?>"
                                                    data-bet="<?php echo htmlspecialchars(json_encode($bet)); ?>"
                                                    data-bet-friend="<?php echo htmlspecialchars(json_encode($friend)); ?>"
                                                    data-not-me="<?php echo htmlspecialchars(json_encode($not_me)); ?>"
                                                    data-not-my-nonprofit="<?php echo htmlspecialchars(json_encode($not_my_nonprofit)); ?>"
                                                    class="btn btn-sm btn-primary btn-claim-loss">I lost :(</button>
                                            <a href="<?php echo $bet->getUrl(); ?>" type="button" class="btn btn-sm btn-primary">VIEW</a>
                                        </div>
                                    <?php elseif ($bet->getClaim() == 'win' && $friend->getClaim() == 'win'): ?>
                                        <div>Both you and <span class="underline bold"><?php echo $not_me->getLink(); ?></span> claim to have won the bet. You can change your mind and take the loss if you want.</div>
                                        <div class="bet-claim-row-cont">
                                            <button type="button"
                                                    data-bet-id="<?php echo $bet->getId(); ?>"
                                                    data-bet="<?php echo htmlspecialchars(json_encode($bet)); ?>"
                                                    data-bet-friend="<?php echo htmlspecialchars(json_encode($friend)); ?>"
                                                    data-not-me="<?php echo htmlspecialchars(json_encode($not_me)); ?>"
                                                    data-not-my-nonprofit="<?php echo htmlspecialchars(json_encode($not_my_nonprofit)); ?>"
                                                    class="btn btn-sm btn-primary btn-claim-loss">Ok darn it! I lost :(</button>
                                            <a href="<?php echo $bet->getUrl(); ?>" type="button" class="btn btn-sm btn-primary">VIEW</a>
                                        </div>
                                    <?php else: ?>
                                        <?php if ($my_claim == 'win'): ?>
                                            YOU WON<br/>
                                            <?php if ($not_my_donation && $not_my_donation->isComplete()): ?>
                                                Your winnings have been donated to <?php echo $not_my_donation->getCharity()->getLink(); ?>
                                            <?php else: ?>
                                                Waiting for your winnings to be donated to <?php echo $my_nonprofit->getLink(); ?>
                                            <?php endif; ?>
                                            <br/>
                                        <?php elseif ($my_claim == 'loss'): ?>
                                            YOU LOST<br/>
                                            <?php if ($my_donation && $my_donation->isComplete()): ?>
                                                You have donated to <?php echo $my_donation->getCharity()->getLink(); ?><br/>
                                            <?php endif; ?>
                                            <?php if ($not_my_claim == 'loss'): ?>
                                                Your friend claims to also have lost.
                                                <?php if ($not_my_donation && $not_my_donation->isComplete()): ?>
                                                    <br/>Your friend donated to <?php echo $not_my_donation->getCharity()->getLink(); ?>.<br/>
                                                <?php else: ?>
                                                    But he has not made a donation yet.<br/>
                                                <?php endif; ?>
                                            <?php endif; ?>

                                            <?php if (!$my_donation || !$my_donation->isComplete()): ?>
                                                You now need to make the donation to <?php echo htmlspecialchars($not_me->getName()); ?>'s nonprofit<br/>
                                                <button type="button"
                                                        class="btn btn-bet-friend-donation btn-primary btn-sm btn-success"
                                                        data-bet="<?php echo htmlspecialchars(json_encode($bet)); ?>"
                                                        data-bet-friend="<?php echo htmlspecialchars(json_encode($friend)); ?>"
                                                        data-not-me="<?php echo htmlspecialchars(json_encode($not_me)); ?>"
                                                        data-not-my-nonprofit="<?php echo htmlspecialchars(json_encode($not_my_nonprofit)); ?>">DONATE!</button>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                        <a href="<?php echo $bet->getUrl(); ?>" type="button" class="btn btn-sm btn-primary">VIEW</a>
                                    <?php endif; ?>
                                <?php else: ?>
                                    ACCEPTED/CONFIRMED<br/>
                                    <a href="<?php echo $bet->getUrl(); ?>" type="button" class="btn btn-sm btn-primary">VIEW</a>
                                <?php endif; ?>
                            <?php elseif ($friend->getStatus() == 'pending'): ?>
                                PENDING<br/>
                                <?php if ($friend->getUser() == $CI->user): ?>
                                    <button data-bet-id="<?php echo $bet->getId(); ?>" type="button" class="btn btn-sm btn-success btn-accept-bet">ACCEPT</button>
                                    <button data-bet-id="<?php echo $bet->getId(); ?>" type="button" class="btn btn-sm btn-info btn-reject-bet">REJECT</button>
                                <?php else: ?>
                                    <a href="<?php echo $bet->getUrl(); ?>" type="button" class="btn btn-sm btn-primary">VIEW</a>
                                <?php endif; ?>
                            <?php elseif ($friend->getStatus() == 'incomplete'): ?>
                                INCOMPLETE<br/>
                                <?php if ($bet->getUser() == $CI->user): ?>
                                    Waiting for <?php echo $friend->getFriend()->getLink(); ?> to pick a nonprofit.
                                <?php elseif ($friend->getUser() == $CI->user): ?>
                                    <div>Select a nonprofit.</div>
                                    <div class="form-group charity-search-container">
                                        <ul class="bet_charity_chosen" style="display: none;"></ul>
                                        <input type="text" class="form-control bet_charity" name="charity" placeholder="Start typing the name of your nonprofit-of-choice..." value="">
                                        <ul class="bet_charity_results" style="display: none;"></ul>
                                    </div>
                                    <button
                                        type="button"
                                        class="btn btn-success btn-pick-charity-submit btn-sm"
                                        data-loading-text="....."
                                        data-bet-id="<?php echo $bet->getId(); ?>">SUBMIT</button>
                                <?php endif; ?>
                            <?php elseif($friend->getStatus() == 'requested'): ?>
	                            <div>Has requested to accept the bet.</div>
	                            <?php if ($CI->user && $bet->getUser() == $CI->user): ?>
		                            <button type="button" data-friend-id="<?php echo $friend->getId(); ?>" class="btn btn-sm btn btn-success btn-accept-bet-request">ACCEPT</button>
		                            <button type="button" data-friend-id="<?php echo $friend->getId(); ?>" class="btn btn-sm btn btn-danger btn-reject-bet-request">REJECT</button>
	                            <?php endif; ?>
                            <?php elseif($friend->getStatus() == 'request_rejected'): ?>
	                            <div>Request to join the bet was rejected.</div>
                            <?php else: ?>
                                <?php echo $bet->getStatus(); ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
</div><!-- table-responsive end -->
