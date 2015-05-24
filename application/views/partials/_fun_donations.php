<?php
/** @var \Base_Controller $CI */
$CI =& get_instance();
?>
<div class="block fun-donations-block">
	<header>Have fun with your donations!</header>
	<div class="numero3"></div>
	<a href="/bet-a-friend"
	   class="btn btn-fun-blue gh-trigger-event bold"
	   data-event-category="button"
	   data-event-action="click"
	   data-event-label="Fun donation Make a bet">
		<span class="friday-night-lights">BET-A-FRIEND</span> for Charity
		<img
			alt="Bet-A-Friend for Charity"
			class="fun-donations-help bs3_popover"
			data-trigger="hover"
			data-content="Know you're right? Make a bet. Set the terms, choose a donation amount, and after the event occurs the winner decides which nonprofit the loser's money goes to."
			data-placement="left"
			src="/images/question1.png">
	</a>

	<a href="/challenge/create"
	   class="btn btn-fun-green gh-trigger-event bold btn-create-challenge-from-block"
	   data-event-category="button"
	   data-event-action="click"
	   data-event-label="Fun donation Create a challenge">
		Create a <span class="foughtknight">CHALLENGE</span>
		<img
			alt="Create a Challenge"
			class="fun-donations-help bs3_popover"
			data-trigger="hover"
			data-content="Challenge up to 3 friends to perform some task within 24 hours from the issuance of the challenge or make a donation. Once they've completed the challenge they will be asked to invite three of their friends. Challenges are a great way to spread awareness for causes (e.g. the ALS Ice-Bucket Challenge)"
			data-placement="left"
			src="/images/question1.png">
	</a>

	<a href="/giver-cards"
	   class="btn btn-fun-red gh-trigger-event bold"
	   data-event-category="button"
	   data-event-action="click"
	   data-event-label="Fun donation Make a bet">
		<span class="dk-cool-crayon">GiverCard</span> e-gift cards
		<img
			alt="Send a GiverCard"
			class="fun-donations-help bs3_popover"
			data-trigger="hover"
			data-content="Give someone the gift of charity. GiverCards are e-gift cards that allow the recipient to choose which of the 2 million nonprofits in GiverHub's database (nearly every nonprofit in the US) they would like the specified funds directed to"
			data-placement="left"
			src="/images/question1.png">
	</a>

</div>
<?php if ($CI->user): ?>
    <?php $CI->load->view('/bets/_modals'); ?>
    <?php $CI->load->view('/partials/_donation_modals'); ?>
<?php endif; ?>
