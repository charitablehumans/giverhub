jQuery(document).ready(function(){
	try {
		jQuery(document).on('click', '.btn-submit-forgot-password', function() {
			var btn = jQuery(this);
			try {
				btn.button('loading');

				var username = jQuery('#forgot_username').val();
				var email = jQuery('#forgot_email').val();

				if (!username.length && !email.length) {
					giverhubError({subject : 'Missing information!' ,msg : 'You need to type a username or email.'});
					btn.button('reset');
					return false;
				}

				if (username.length && email.length) {
					giverhubError({subject : 'Too much information!' ,msg : 'You need to type ONLY ONE of username and email.'});
					btn.button('reset');
					return false;
				}

				var data = {};
				if (username.length) {
					data = { username : username };
				}
				if (email.length) {
					data = { email : email };
				}

				jQuery.ajax({
					url : '/home/forgot_password',
					type : 'post',
					dataType : 'json',
					data : data,
					error : function() {
						giverhubError({msg : 'Request Failed.'});
					},
					success : function(json) {
						try {
							if (json === undefined || !json || json.success === undefined || !json.success || json.msg === undefined) {
								if (json.success === false) {
									giverhubError({subject : 'Forgot Password', msg : json.msg});
								} else {
									giverhubError({msg : 'Bad response'});
								}
							} else {
								giverhubSuccess({msg : 'An email with instructions on how to reset your password has been sent to '+json.msg+'.'});
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
				btn.button('reset');
				giverhubError({e:e});
			}
			return false;
		});

		jQuery(document).on('click', '.btn-forgot-password-from-settings', function() {
			var btn = jQuery(this);
			try {
				if (btn.html() == 'Wait...') {
					return false;
				}
				btn.html('Wait...');

				jQuery.ajax({
					url : '/home/forgot_password',
					type : 'post',
					dataType : 'json',
					data : { email : btn.data('email') },
					error : function() {
						giverhubError({msg : 'Request Failed.'});
					},
					success : function(json) {
						try {
							if (json === undefined || !json || json.success === undefined || !json.success || json.msg === undefined) {
								if (json.success === false) {
									giverhubError({subject : 'Forgot Password', msg : json.msg});
								} else {
									giverhubError({msg : 'Bad response'});
								}
							} else {
								giverhubSuccess({msg : 'An email with your new password has been sent to '+json.msg+'.'});
							}
						} catch(e) {
							giverhubError({e:e});
						}
					},
					complete : function() {
						btn.html('Reset Again?');
					}
				});
			} catch(e) {
				giverhubError({e:e});
			}
			return false;
		});

		jQuery(document).on('click', '.btn-submit-reset-password', function() {
			var btn = jQuery(this);
			try {
				btn.button('loading');
				var pass1 = jQuery('#reset_password_1').val();
				var pass2 = jQuery('#reset_password_2').val();
				if (!pass1 || pass1 != pass2) {
					giverhubError({subject : 'Passwords needs to match.', msg : 'Passwords needs to match each other.'});
					btn.button('reset');
					return false;
				}

				jQuery.ajax({
					url : '/home/reset_password_submit',
					type : 'post',
					dataType : 'json',
					data : {password : pass1, token : btn.data('token') },
					error : function() {
						giverhubError({msg : 'Request failed!'});
					},
					success : function(json) {
						try {
							if (json === undefined || !json || json.success === undefined || !json.success) {
								giverhubError({msg : 'Bad response from server.'});
							} else {
								window.location = '/';
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