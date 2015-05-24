<?php
/** @var \Entity\FAQ[] $faqs */
$this->load->view('/givercards/_header'); 

?>

<main class="members_main" role="main" id="main_content_area">
	<section class="container">
		<form name="giver_cards" action="#" method="post" id="giver_cards">
			<div class="block giver-cards-block">
				<div class="row">
					<h2 class="LobsterTwo_Italic">Step 1: Who is the recipient?</h2>
					<div class="gh_spacer_7"><u>If they are already a GiverHub user</u>, start typing their name in the field below and select their name			</div>
					<div class="form-group">
					<ul class="giver_card_friend_chosen" style="display: none;"></ul>
			      	<input type="text" class="form-control giver_card_to_user" name="existing_email" placeholder="Start typing a friend's name: e.g. Sam..." >
					<ul class="giver_card_friend_results" style="display: none;"></ul>
		      </div>
				
					<div class="gh_spacer_7"><u>If the recipient is NOT already a GiverHub user</u>, enter their name and email in the field below</div>
					<div class="form-group">
					<div class="col-md-4 givercard_new_name"><input type="text" class="form-control new_user_name" name="new_name" placeholder="Enter name" ></div>
					<div class="col-md-8 givercard_new_email"><input type="text" class="form-control new_user_email" name="new_email" placeholder="Enter email" ></div>
		      </div>
				</div>
			</div>
			<div class="block giver-cards-block">
				<div class="row">
					<h2 class="LobsterTwo_Italic">Step 2: Select a donation amount for the GiverCard</h2>
					<div class="gh_spacer_7">Enter the amount you would like the recipient to be able to donate in the field below (Minumum $10)</div>
					<div class="form-group">
						<span class="giver-cards-donation-amount-sign">$</span>
			      <input type="text" class="giver-cards-donation-amount-text form-control" name="donation_amount" placeholder="Enter Amount">
		      </div>
				</div>
			</div>
			<div class="block giver-cards-block">
				<div class="row">
					<h2 class="LobsterTwo_Italic">Step 3: Include a message</h2>
					<div class="gh_spacer_7">Tell the recipient why you're giving them a GiverCard</div>
					<div class="form-group">
						<textarea class="form-control giver_card_message" name="message" placeholder="e.g. Happy Birthday!" title=""></textarea>
			    	</div>
				</div>
			</div>
			<div class="block giver-cards-block">
				<div class="row">
						<h2 class="LobsterTwo_Italic">You're all done!</h2>
						<div class="gh_spacer_7">Just click the "PREVIEW" button to preview the GiverCard that will be sent</div>
						<button class="btn btn-primary btn-large preview-giver-cards" data-target-form="#giver_cards">PREVIEW</button>
				</div>
			</div>	
			<div class="row gh_spacer_14">
				<div class="col-md-12 giver_card_error"></div>
			</div>
		</form>  		
	</section>
</main>

<?php $this->load->view('givercards/_modals'); ?>

