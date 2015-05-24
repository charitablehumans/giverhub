<?php
$CI =& get_instance();
/** @var \Base_Controller $CI */
/** @var \Entity\Bet $bet */
?>
<div class="from-to-container">
    <div class="from from-to vegur_light">
        <span class="from-to-label">From:</span>
        <div class="submitted-by-container">
            <div class="pull-left user-container">
                <a href="/">
                    <img src="<?php echo $bet->getUser()->getImageUrl(); ?>" alt="<?php echo htmlspecialchars($bet->getUser()->getName()); ?>">
                    <span class="name"><?php echo htmlspecialchars($bet->getUser()->getName()); ?></span>
                </a>
            </div>
        </div>
        <p><?php echo htmlspecialchars($bet->getUser()->getFname()); ?>'s nonprofit <?php echo $bet->getCharity()->getLink(); ?></p>
    </div>
    <?php foreach($bet->getFriends() as $friend): ?>
        <div class="to from-to vegur_light">
            <span class="from-to-label">To:</span>
            <div class="submitted-by-container">
                <div class="pull-left user-container">
                    <a href="/">
                        <img src="<?php echo $friend->getFriend()->getImageUrl(); ?>" alt="<?php echo htmlspecialchars($friend->getFriend()->getName()); ?>">
                        <span class="name"><?php echo htmlspecialchars($friend->getFriend()->getName()); ?></span>
                    </a>
                </div>
            </div>
            <div class="friend-status-block">
                <?php if ($friend->getStatus() == 'pending'): ?>
                    <?php if ($CI->user && $friend->getFriend() == $CI->user): ?>
                        <p class="vegur_light">
                            <span class="vegur_regular">If you accept the bet</span>, then on
                            <span class="vegur_regular"><?php echo $bet->getDeterminationDate()->format('d/m/y'); ?> </span>
                            you and <?php echo htmlspecialchars($bet->getUser()->getFname()); ?> will determine a winner and the loser will donate to the winner's nonprofit
                        </p>
                        <div class="buttons">
                            <div class="col-md-6">
                                <button
                                    data-bet-id="<?php echo $bet->getId(); ?>"
                                    <?php if (!$CI->user): ?>data-toggle="modal" data-target="#signin-or-join-first-modal"<?php endif; ?>
                                    class="bet-info-page btn btn-lg btn-success <?php if ($CI->user): ?>btn-accept-bet<?php endif; ?>"
                                    data-loading-text="ACCEPT"
                                    type="button">ACCEPT</button>
                            </div>
                            <div class="col-md-6">
                                <button
                                    data-bet-id="<?php echo $bet->getId(); ?>"
                                    <?php if (!$CI->user): ?>data-toggle="modal" data-target="#signin-or-join-first-modal"<?php endif; ?>
                                    class="bet-info-page btn btn-lg btn-danger <?php if ($CI->user): ?>btn-reject-bet<?php endif; ?>"
                                    data-loading-text="REJECT"
                                    type="button">REJECT</button>
                            </div>
                        </div>
                    <?php else: ?>
                        <p class="vegur_light">
                            This bet is pending acceptance from <?php echo htmlspecialchars($friend->getFriend()->getName()); ?>
                        </p>
                    <?php endif; ?>
                <?php elseif($friend->getStatus() == 'accepted'): ?>
                    <?php if ($bet->isTimeForDetermination()): ?>
                        <?php if ($CI->user && $bet->isToUser($CI->user) || $bet->getUser() == $CI->user): ?>
                            <?php $not_my_nonprofit = $bet->getUser() == $CI->user ? $friend->getCharity() : $bet->getCharity(); ?>
                            <?php $my_nonprofit = $bet->getUser() == $CI->user ? $bet->getCharity() : $friend->getCharity(); ?>
                            <?php $not_me = $bet->getUser() == $CI->user ? $friend->getFriend() : $bet->getUser(); ?>
                            <?php $my_claim = $bet->getMyClaim($CI->user); ?>
                            <?php $not_my_claim = $friend->getNotMyClaim($CI->user); ?>
                            <?php $my_donation = $friend->getMyDonation($CI->user); ?>
                            <?php $not_my_donation = $friend->getNotMyDonation($CI->user); ?>

                            <?php if ($my_claim === null): ?>
                                <p class="vegur_light">It's time to determine the winner of your bet!</p>
                                <div class="bet-claim-row-cont">
                                    <button type="button" data-bet-id="<?php echo $bet->getId(); ?>" class="btn btn-sm btn-success btn-claim-win">I won! :)</button>
                                    <button type="button"
                                            data-bet-id="<?php echo $bet->getId(); ?>"
                                            data-bet="<?php echo htmlspecialchars(json_encode($bet)); ?>"
                                            data-bet-friend="<?php echo htmlspecialchars(json_encode($friend)); ?>"
                                            data-not-me="<?php echo htmlspecialchars(json_encode($not_me)); ?>"
                                            data-not-my-nonprofit="<?php echo htmlspecialchars(json_encode($not_my_nonprofit)); ?>"
                                            class="btn btn-sm btn-primary btn-claim-loss">I lost :(</button>
                                </div>
                            <?php elseif ($bet->getClaim() == 'win' && $friend->getClaim() == 'win'): ?>
                                <p class="vegur_light">Both you and <span class="underline bold"><?php echo $not_me->getLink(); ?></span> claim to have won the bet. You can change your mind and take the loss if you want.</p>
                                <div class="bet-claim-row-cont">
                                    <button type="button"
                                            data-bet-id="<?php echo $bet->getId(); ?>"
                                            data-bet="<?php echo htmlspecialchars(json_encode($bet)); ?>"
                                            data-bet-friend="<?php echo htmlspecialchars(json_encode($friend)); ?>"
                                            data-not-me="<?php echo htmlspecialchars(json_encode($not_me)); ?>"
                                            data-not-my-nonprofit="<?php echo htmlspecialchars(json_encode($not_my_nonprofit)); ?>"
                                            class="btn btn-sm btn-primary btn-claim-loss">Ok darn it! I lost :(</button>
                                </div>
                            <?php else: ?>
                                <p class="vegur_light">
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
                                </p>
                            <?php endif; ?>
                        <?php else: ?>
                            <p class="vegur_light">
                                <?php if ($bet->getClaim() === null && $friend->getClaim() === null): ?>
                                    It's time to determine who won this bet.
                                <?php else: ?>
                                    <?php if ($bet->getClaim() == 'win'): ?>
                                        <?php echo $bet->getUser()->getLink(); ?> claims to have won the bet.
                                    <?php elseif ($bet->getClaim() == 'loss'): ?>
                                        <?php echo $bet->getUser()->getLink(); ?> claims to have lost the bet.<br/>
                                        <?php if ($friend->getMyDonation($bet->getUser()) && $friend->getMyDonation($bet->getUser())->isComplete()): ?>
                                            <?php echo $bet->getUser()->getLink(); ?> has donated to <?php echo $friend->getMyDonation($bet->getUser())->getCharity()->getLink(); ?><br/>
                                        <?php else: ?>
                                            <?php echo $bet->getUser()->getLink(); ?> has not donated yet.
                                        <?php endif; ?>
                                    <?php endif; ?>

                                    <?php if ($friend->getClaim() == 'win'): ?>
                                        <?php echo $friend->getUser()->getLink(); ?> claims to have won the bet.
                                    <?php elseif ($friend->getClaim() == 'loss'): ?>
                                        <?php echo $friend->getUser()->getLink(); ?> claims to have lost the bet.<br/>
                                        <?php if ($friend->getMyDonation($friend->getUser()) && $friend->getMyDonation($friend->getUser())->isComplete()): ?>
                                            <?php echo $friend->getUser()->getLink(); ?> has donated to <?php echo $friend->getMyDonation($friend->getUser())->getCharity()->getLink(); ?><br/>
                                        <?php else: ?>
                                            <?php echo $friend->getUser()->getLink(); ?> has not donated yet.
                                        <?php endif; ?>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </p>
                        <?php endif; ?>
                    <?php else: ?>
                        <p class="vegur_light">
                            <span class="vegur_regular">Great!</span> <?php $friend->getFriend()->getLink(); ?> <span class="vegur_regular">accepted/confirmed!</span><br/>
                            <span class="vegur_regular">Winnings will be donated to <?php echo $friend->getCharity()->getLink(); ?></span>
                        </p>
                    <?php endif; ?>
                <?php elseif($friend->getStatus() == 'rejected'): ?>
                    <p class="vegur_light">
                        <?php $friend->getFriend()->getLink(); ?> <span class="vegur_regular">rejected!</span>
                    </p>
                <?php elseif ($friend->getStatus() == 'incomplete'): ?>
                    <?php if ($CI->user && $friend->getFriend() == $CI->user): ?>
                        <p class="vegur_light">
                            <span class="vegur_regular">Nice</span>, you have accepted the bet. Now you just need to <span class="vegur_regular">pick your nonprofit.</span>
                        </p>
                        <div>Select a nonprofit.</div>
                        <div class="form-group charity-search-container">
                            <ul class="bet_charity_chosen" style="display: none;"></ul>
                            <input type="text" class="form-control bet_charity" name="charity" placeholder="Start typing the name of your nonprofit-of-choice..." value="">
                            <ul class="bet_charity_results" style="display: none;"></ul>
                        </div>
                        <button
                            type="button"
                            class="bet-info-page btn btn-success btn-pick-charity-submit btn-sm"
                            data-loading-text="....."
                            data-bet-id="<?php echo $bet->getId(); ?>">SUBMIT</button>
                    <?php else: ?>
                        <p class="vegur_light">
                            This bet is incomplete. <?php echo htmlspecialchars(ucfirst($friend->getFriend()->getName())); ?> needs to select a nonprofit.
                        </p>
                    <?php endif; ?>
                <?php elseif($friend->getStatus() == 'requested'): ?>
	                <p class="vegur_light">
		                Has requested to accept the bet.
	                </p>
	                <?php if ($CI->user && $bet->getUser() == $CI->user): ?>
						<button type="button"
						        data-friend-id="<?php echo $friend->getId(); ?>"
						        data-loading-text="ACCEPT"
						        class="btn btn-sm btn btn-success btn-accept-bet-request">ACCEPT</button>
		                <button type="button"
		                        data-friend-id="<?php echo $friend->getId(); ?>"
		                        data-loading-text="REJECT"
		                        class="btn btn-sm btn btn-danger btn-reject-bet-request">REJECT</button>
	                <?php endif; ?>
                <?php elseif($friend->getStatus() == 'request_rejected'): ?>
	                <p class="vegur_light">
		                Request to join the bet was rejected.
	                </p>
                <?php endif; ?>
            </div>
        </div>

    <?php endforeach; ?>
</div>
<p class="vegur_light">
    <?php if ($CI->user && $bet->getUser() == $CI->user): ?>
        You bet that <span class="vegur_regular"><?php echo htmlspecialchars($bet->getTerms(['remove_ending_dot' => true])); ?>.</span>
    <?php elseif ($CI->user && $bet->isToUser($CI->user)): ?>
        <?php echo htmlspecialchars($bet->getUser()->getFname()); ?> bets you that <span class="vegur_regular"><?php echo htmlspecialchars($bet->getTerms(['remove_ending_dot' => true])); ?>.</span>
    <?php else: ?>
        <?php echo htmlspecialchars($bet->getUser()->getFname()); ?> bets that <span class="vegur_regular"><?php echo htmlspecialchars($bet->getTerms(['remove_ending_dot' => true])); ?>.</span>
    <?php endif; ?>
</p>

<?php if ($bet->getStatus() == 'draft'): ?>
    <p class="vegur_light">This bet is just a <span class="vegur_regular">draft.</span></p>
<?php endif; ?>
