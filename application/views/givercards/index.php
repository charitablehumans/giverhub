<?php
/** @var \Base_Controller $this */
$this->load->view('/givercards/_header');
?>

<main class="members_main" role="main" id="main_content_area">
	<section class="container">
		<form name="giver_cards" action="#" method="post" id="giver_cards">
			<div class="row">
				<div class="col-sm-5">
					<div class="block giver-cards-block">
						<div class="col-md-8 col-sm-7 col-xs-7"><h2 class="LobsterTwo_Italic">Create a GiverCard</h2></div>
						<div class="col-md-4 col-sm-5 col-xs-5">
							<a href="/giver-cards/create" class="btn btn-primary btn-large create-giver-cards pull-right">create</a>
						</div>
					</div>
				</div>
				<?php if (!GIVERHUB_LIVE): ?>
				<div class="col-sm-5 create-giving-pot-wrapper gh_popover"
				     data-trigger="hover"
				     data-placement="top"
				     data-title="About Giving Pots"
				     data-content="A Giving Pot enables a user or business to set aside a certain amount of money for donations, and allocate specific amounts to different users">
					<div class="block giver-cards-block">
						<div class="col-md-8 col-sm-7 col-xs-7"><h2 class="LobsterTwo_Italic">Create a Giving Pot</h2></div>
						<div class="col-md-4 col-sm-5 col-xs-5">
							<a href="/giving-pot/about" class="btn btn-primary btn-large create-giver-cards pull-right">create</a>
						</div>
					</div>
				</div>
				<?php endif; ?>
			</div>

			<?php if ($this->user->getGivingPots()): ?>
				<div class="row">
					<div class="col-xs-12">
						<div class="block giver-cards-block table-block">
							<div class="row">
								<h2 class="LobsterTwo_Italic">Your Giving Pot(s)</h2>
								<div class="gh_spacer_7">
									<table class="giving-pots-table table table-hover">
										<tbody>
											<tr class="givercard-sent-listing">
												<td class="summary">Summary</td>
												<td class="pot-size">Original Pot Size</td>
												<td class="remaining">Amount Remaining</td>
												<td class="buttons">&nbsp;</td>
											</tr>
										<?php foreach($this->user->getGivingPots() as $giving_pot): ?>
											<tr>
												<td class="summary">
													<?php if ($giving_pot->getSummary()): ?>
														<?php echo htmlspecialchars($giving_pot->getSummary()); ?>
													<?php elseif (!$giving_pot->isPublished()): ?>
														This Giving Pot draft has not been published yet.
													<?php else: ?>
														No summary
													<?php endif; ?>
												</td>
												<td class="pot-size">
													<?php if (!$giving_pot->getPotSize()): ?>
														-
													<?php else: ?>
														<?php echo '$'.$giving_pot->getPotSize(); ?>
													<?php endif; ?>
												</td>
												<td class="remaining">
													<?php if (!$giving_pot->getAmountRemaining()): ?>
														-
													<?php else: ?>
														<?php echo '$'.$giving_pot->getAmountRemaining(); ?>
													<?php endif; ?>
												</td>
												<td class="buttons">
													<?php if ($giving_pot->isPublished()): ?>
														<a href="/giving-pot/dashboard/<?php echo $giving_pot->getId(); ?>">View Dashboard/Add Recipients</a>
													<?php else: ?>
														<a href="/giving-pot/edit/<?php echo $giving_pot->getId(); ?>">Edit Draft</a>
													<?php endif; ?>
												</td>
											</tr>
										<?php endforeach; ?>

										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			<?php endif; ?>

			<div class="row">
				<div class="col-xs-12">
					<div class="block giver-cards-block table-block">
						<div class="row">
							<h2 class="LobsterTwo_Italic">Your Sent GiverCards</h2>
							<?php if ( count($sent_givercards) ) : ?>
							<div class="gh_spacer_7">
								<table class="table table-hover">
									<tbody>
										<tr class="givercard-sent-listing"><td>Recipient</td><td>Send date</td><td>Original Amount</td><td>Amount Spent</td></tr>
										<!-- Code for showing sent givercards for logged in user -->
										<?php
										foreach($sent_givercards as $sentGivercards) :
											$receipient = $sentGivercards->getToUserId();
											if ($receipient) :
												$getReceipientName 	= \Entity\User::findOneBy(array('id' => $receipient));
												$receipientName 	= $getReceipientName->getFname().' '.$getReceipientName->getLname();
											elseif ($sentGivercards->getToEmail()) :
												$receipientName = $sentGivercards->getToEmail();
											else:
											    $receipientName = $sentGivercards->getTo()->getName();
											endif;
											$amountSpent = $sentGivercards->getAmount() - $sentGivercards->getBalanceAmount();
										?>
										<tr>
											<td><?php echo $receipientName;?></td>
											<td><?php echo date('m/d/Y',strtotime($sentGivercards->getDateCreated()) );?></td>
											<td><?php echo $sentGivercards->getAmount();?></td>
											<td><?php echo $amountSpent;?></td>
										</tr>
										<?php endforeach; ?>

									</tbody>
								</table>
							</div>
							<?php else: ?>
							<div class="gh_spacer_7 no_givercards">You have not sent any GiverCards</div>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-xs-12">
					<div class="block giver-cards-block table-block">
						<div>
							<h2 class="LobsterTwo_Italic">Your Received GiverCards</h2>
							<?php if ( count($received_givercards) ) : ?>
							<div class="gh_spacer_7">
								<table class="table table-hover" id="received_givercards_table">
									<tr class="givercard-sent-listing">
										<td>Sender</td><td>Send date</td><td>Original Amount</td><td>Amount Spent</td><td>&nbsp;</td></td>
									</tr>
									<!-- Code for showing received givercards for logged in user -->
									<?php
									foreach($received_givercards as $receivedGivercards) :
										$getReceipientName 	= \Entity\User::findOneBy(array('id' => $receivedGivercards->getFromUserId() ));
										$senderName	= $getReceipientName->getFname().' '.$getReceipientName->getLname();
										$amountSpent = $receivedGivercards->getAmount() - $receivedGivercards->getBalanceAmount();
									?>
									<tr class="received_givercard_listing_tr" data-url="/giver-cards/view_giverCard/<?php echo $receivedGivercards->getId(); ?>">
										<td><?php echo $senderName;?></td>
										<td><?php echo date('m/d/Y',strtotime($receivedGivercards->getDateCreated()) ); ?></td>
										<td><?php echo $receivedGivercards->getAmount();?></td>
										<td><?php echo $amountSpent;?></td>
		                                <td><a href="/giver-cards/view_giverCard/<?php echo $receivedGivercards->getId(); ?>">View GiverCard</a></td>
									</tr>
									<?php endforeach; ?>
								</table>
							</div>
							<?php else: ?>
								<div class="gh_spacer_7 no_givercards">You have not received any GiverCards</div>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>

			<div class="row gh_spacer_14">
				<div class="col-md-12 giver_card_error"></div>
			</div>
		</form>
	</section>
</main>

