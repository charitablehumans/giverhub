try {
	jQuery(document).ready(function() {
		try {
			var $add_outside_donation_modal = jQuery('#add-outside-donation-modal');

			if ($add_outside_donation_modal.length) {
				jQuery(document).on('click', '.btn-add-outside-donation', function () {
					try {
						$add_outside_donation_modal.modal('show');
						$add_outside_donation_modal.find('form').find('.nonprofit').focus();
					} catch(e) {
						giverhubError({e:e});
					}
				});

				$add_outside_donation_modal.find('form').find('.donation-date').datetimepicker({
					pickTime: false,
					maxDate : moment()
				});
				$add_outside_donation_modal.find('form').find('.time').datetimepicker({
					pickTime: true,
					pickDate: false
				});

				$add_outside_donation_modal.on('click', '.btn-submit-outside-donation', function() {
					var $this = jQuery(this);
					try {
						var $form = $add_outside_donation_modal.find('form');

						var outside_donation = {};

						var $nonprofit = $form.find('.nonprofit');
						var nonprofit = $nonprofit.val().trim();
						if (!nonprofit.length) {
							giverhubError({
								subject : 'Nonprofit name is required.',
								msg : 'Please enter the name of the nonprofit.'
							});
							giverhubError.hideEvent = function () {
								$nonprofit.focus();
							};
							return false;
						}
						outside_donation.nonprofit = nonprofit;

						var $amount = $form.find('.amount');
						var amount = $amount.val().trim();
						if (!amount.length) {
							giverhubError({
								subject : 'Amount is required.',
								msg : 'Please enter the amount.'
							});
							giverhubError.hideEvent = function () {
								$amount.focus();
							};
							return false;
						}
						if (isNaN(parseInt(amount))) {
							giverhubError({
								subject : 'Invalid Amount.',
								msg : 'Please enter the amount using only numbers. Without dollar signs, commas, periods.'
							});
							giverhubError.hideEvent = function () {
								$amount.focus();
							};
							return false;
						}
						outside_donation.amount = amount;

						var $date = $form.find('.donation-date');
						var date = $date.val().trim();
						var date_moment = moment(date, 'MM/DD/YYYY');
						if (!date_moment.isValid()) {
							giverhubError({
								subject : 'Invalid Date',
								msg : 'You need to select a date.'
							});
							giverhubError.hideEvent = function() {
								$date.focus();
							};
							return false;
						}
						outside_donation.date = date_moment.format('YYYY-MM-DD');

						outside_donation.time = null;

						var $time = $form.find('.time');
						var time = $time.val().trim();
						if (time.length) {
							var time_moment = moment(time, 'h:mm A');
							if (!time_moment.isValid()) {
								giverhubError({
									subject : 'Invalid time',
									msg : 'You need to select a time.'
								});
								giverhubError.hideEvent = function () {
									$time.focus();
								};
								return false;
							} else {
								outside_donation.time = time_moment.format('HH:mm');
							}
						}

						outside_donation.cause = null;
						var $cause = $form.find('.cause');
						var cause = $cause.val().trim();

						if (cause.length) {
							outside_donation.cause = cause;
						}

						$this.button('loading');

						jQuery.ajax({
							url : '/outside_donation/add',
							dataType : 'json',
							type : 'post',
							data : outside_donation,
							error : function() {
								giverhubError({msg : 'Request Failed'});
							},
							complete : function() {
								$this.button('reset');
							},
							success : function(json) {
								try {
									checkSuccess(json);

									if (typeof(json.donation_history_html) !== "string") {
										giverhubError({msg : 'Bad response!'});
									} else {
										jQuery('.donation-history-tbody').html(json.donation_history_html);
										$add_outside_donation_modal.modal('hide');
										$form[0].reset();
									}
								} catch(e) {
									giverhubError({e:e});
								}
							}
						});
					} catch(e) {
						$this.button('reset');
						giverhubError({e:e});
					}
				});
			}
		} catch(e) {
			giverhubError({e:e});
		}
	});
} catch(e) {
	giverhubError({e:e});
}