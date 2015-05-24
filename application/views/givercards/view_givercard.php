<?php
/** @var \Entity\GivercardTransactions $givercard */

/** @var \Base_Controller $CI */
$CI =& get_instance();
$this->load->view('/givercards/_header');
?>

<main class="members_main" role="main" id="main_content_area">
	<section class="container">
		<form name="giver_cards" action="#" method="post" id="giver_cards">
			<div class="block giver-cards-block">
				<div class="row">
					<h2 class="LobsterTwo_Italic view_givercard_page_hr">This is <?php echo htmlspecialchars(ucfirst($givercard->getFromUser()->getFname())); ?>'s GiverCard to You</h2>
				</div>

				<div class="row view_givercard_page_text view_givercard_page_hr">
					You have $<span class="givercard_balance_amount"><?php echo $givercard->getBalanceAmount();?></span> left on this GiverCard
				</div>
				
				<div class="row view_givercard_page_text view_givercard_page_hr">
					<?php echo htmlspecialchars(ucfirst($givercard->getFromUser()->getFname())); ?>'s message to you: <i><?php echo htmlspecialchars($givercard->getMessage()); ?></i>
				</div>


				<!-- Code to show message when current givercard has no amount to donate -->
		        <?php if (!$givercard->getBalanceAmount()): ?>
					<div class="row view_givercard_page_text gh_spacer_14">
						Just because this GiverCard is all used up doesn't mean the giving has to stop! Pay this GiverCard forward by sending one to someone else, issue a Challenge, or make Bet!
					</div>
                    <?php $this->load->view('/partials/_fun_donations', ['my_dashboard' => 1, 'user' => $CI->user]); ?>
                <?php else: ?>
                    <!-- Displayed below boxes if current givercard has some amount to donate to nonprofit otherwise hide it -->
                    <div class="row view_givercard_page_hr gh_spacer_14">
                        <p class="view_givercard_page_text">What nonprofit would you like to donate to? <span class="help_message">You don't have to donate all the money to one nonprofit</span></p>
                        <p><div class="form-group charity-search-container">
                            <ul class="bet_charity_chosen" style="display: none;"></ul>
                            <input type="text" class="form-control bet_charity" name="charity" id="charity" placeholder="Start typing a non-profit's name: e.g. An..." value="" tabindex="6" style="display: block;">
                            <ul class="bet_charity_results" style="display: none;"></ul></div>
                        </p>

                        <span class="help_message_donate">Use the search bar at the top of the page or
                            <form id="members_new_nav_nonprofits_view" action="/search" method="post">
                                <input type="hidden" name="non-profit-tab" value="non-profit-tab">
                                <a href="#" class="members_new_nav_nonprofits view_givercard_nonprofits_link">go here</a>
                            </form> to search nonprofits by name or keyword and view information on them like overall score, financial data, etc.</span>

                    </div>

                    <div class="row view_givercard_page_text">
                        <p>How much would like to donate? <span class="help_message">Minimum Donation: $10</span>
                            <form action="#" class="gh_donation">
                                <div class="col-md-2 col-sm-4 givercard_new_name"><input type="text" class="form-control givercard-page-donation-amount-input" placeholder="Enter Amount $"></div>
                                <div class="col-md-10 col-sm-8"><a data-charity-id="" data-charity-name="" href="#" class="btn btn-warning btn-donate-from-givercard-view-page clear-md" data-loading-text="PROCESSING..." style="margin-right:15px;">DONATE</a></div>
                            </form>
                        </p>
                    </div>
                    <input type="hidden" name="givercard_cof_id" id="givercard_cof_id" value="<?php echo $givercard->getCofId(); ?>">
                    <input type="hidden" name="givercard_transaction_id" id="givercard_transaction_id" value="<?php echo $givercard->getId();?>">
		        <?php endif; ?>
			</div>
		</form>
	</section>
</main>


<?php
    $CI->load->view('/bets/_modals');
    $CI->load->view('/partials/_donation_modals');
