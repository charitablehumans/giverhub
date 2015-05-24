try {
	jQuery(document).ready(function() {
		try {
			var $learn_more_block = jQuery('.learn-more-block');

			$learn_more_block.on('click', 'button', function() {
				try {
					var $this = jQuery(this);

					$this.attr('disabled','disabled');

					if ($this.hasClass('btn-learn-more-about-bets')) {
						window.giverhubStat("learn-more-about-bets", function() {
							window.location = '/bet-a-friend';
						});
					} else if ($this.hasClass('btn-learn-more-about-givercards')) {
						window.giverhubStat("learn-more-about-givercards", function() {
							window.location = '/giver-cards';
						});
					}
				} catch(e) {
					giverhubError({e:e});
				}
			});
		} catch(e) {
			giverhubError({e:e});
		}
	});
} catch(e) {
	giverhubError({e:e});
}