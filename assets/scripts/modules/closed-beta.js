jQuery(document).ready(function() {
	try {
		if (!body.data('signed-in')) {
			jQuery('#closed-beta-signin-modal').modal('show').on('hide.bs.modal', function () {
				return false;
			});
		}

		jQuery(document).on('click', '.btn-submit-closed-beta-signup-email', function() {
			var btn = jQuery(this);
			try {
				btn.button('loading');

				var email = jQuery('#closed-beta-signup-email-input');
				if (email.val().length < 3) {
					giverhubError({subject : 'Invalid Email.', msg : 'Please enter a valid email address.'});
					btn.button('reset');
					return false;
				}

				jQuery.ajax({
					url : '/home/closed_beta_signup',
					dataType : 'json',
					type : 'post',
					data : {email : email.val()},
					error : function() {
						giverhubError({msg : 'How embarrassing, the request failed. Please try again later.'})
					},
					success : function(json) {
						try {
							if (json === undefined || !json || json.success === undefined || !json.success) {
								if (json !== undefined && json.msg !== undefined) {
									giverhubError({subject : 'Something went wrong.', msg : json.msg});
								} else {
									giverhubError({msg : 'How embarrassing, we got a bad response from the server. Please try again later.'});
								}
							} else {
								giverhubSuccess({msg : "Thanks so much! You've been added to the waitlist! We'll notify you as soon as we're able to give you access to the site."});
								email.val('');
							}
						} catch(e) {
							giverhubError({e:e});
						}
					},
					complete : function() {
						btn.button('reset');
					}
				});
			} catch(e) {
				giverhubError({e:e});
				btn.button('reset');
			}
			return false;
		});
	} catch(e) {
		giverhubError({e:e});
	}
});