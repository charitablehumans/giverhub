
var body = jQuery('body');
var $body = body;

jQuery(document).ready(function() {
	body = $body = jQuery('body');
});

var GIVERHUB_DEBUG = body.data('giverhub-debug');
var GIVERHUB_LIVE = body.data('giverhub-live');

var currentUrl = String(jQuery('#current-url')[0]).replace('#', '');

window.parseUri = function(str) {
	var	o   = parseUri.options,
		m   = o.parser[o.strictMode ? "strict" : "loose"].exec(str),
		uri = {},
		i   = 14;

	while (i--) uri[o.key[i]] = m[i] || "";

	uri[o.q.name] = {};
	uri[o.key[12]].replace(o.q.parser, function ($0, $1, $2) {
		if ($1) uri[o.q.name][$1] = $2;
	});

	return uri;
};

window.parseUri.options = {
	strictMode: false,
	key: ["source","protocol","authority","userInfo","user","password","host","port","relative","path","directory","file","query","anchor"],
	q:   {
		name:   "queryKey",
		parser: /(?:^|&)([^&=]*)=?([^&]*)/g
	},
	parser: {
		strict: /^(?:([^:\/?#]+):)?(?:\/\/((?:(([^:@]*)(?::([^:@]*))?)?@)?([^:\/?#]*)(?::(\d*))?))?((((?:[^?#\/]*\/)*)([^?#]*))(?:\?([^#]*))?(?:#(.*))?)/,
		loose:  /^(?:(?![^:@]+:[^:@\/]*@)([^:\/?#.]+):)?(?:\/\/)?((?:(([^:@]*)(?::([^:@]*))?)?@)?([^:\/?#]*)(?::(\d*))?)(((\/(?:[^?#](?![^?#\/]*\.[^?#\/.]+(?:[?#]|$)))*\/?)?([^?#\/]*))(?:\?([^#]*))?(?:#(.*))?)/
	}
};

function requireSignedIn() {
	if (!body.data('signed-in')) {
		jQuery('#signin-or-join-first-modal').modal('show');
		return false;
	}
	return true;
}

function requireSignedIn2(msg) {
	if (!body.data('signed-in')) {
		window.signInOrJoinFirst(msg);
		return false;
	}
	return true;
}

function giverhubSuccess(options) {
	if (!options) {
		throw "missing options";
	}

	var $success_modal = jQuery('#giverhub-success-modal');
	var $subject = jQuery('#giverhub-success-modal-subject');

	if (!options.msg) {
		throw "missing options msg";
	}

	var msg = jQuery('#giverhub-success-modal-msg');
	msg.html(options.msg);


	if (options.hideSubject === undefined) {
		options.hideSubject = false;
	}
	if (options.hideSubject) {
		$subject.hide();
	} else {
		if (!options.subject) {
			options.subject = $subject.data('default-subject');
		}
		$subject.html(options.subject);
		$subject.show();
	}

	var $ok_button = $success_modal.find('.ok-button');
	if (options.okButtonTitle === undefined) {
		options.okButtonTitle = $ok_button.data('default-text');
	}
	$ok_button.html(options.okButtonTitle);


	if (options['facebook-share']) {

		$success_modal.find('.success-facebook-share').removeClass('hide');

		giverhubSuccess['facebook-share'] = options['facebook-share'];
	} else {
		$success_modal.find('.success-facebook-share').addClass('hide');

		this['facebook-share'] = function() {};
	}

	jQuery('.success-facebook-share-message').addClass('hide');

	$success_modal.modal('show');
}

/** jQuery.browser.mobile (http://detectmobilebrowser.com/)
 *  jQuery.browser.mobile will be true if the browser is a mobile device */
(function(a){(jQuery.browser=jQuery.browser||{}).mobile=/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4))})(navigator.userAgent||navigator.vendor||window.opera);
/** end of jQuery.browser.mobile ^ */


jQuery(document).ready(function() {
	try {
		jQuery('#giverhub-success-modal').on('click', '.btn-success-share-facebook', function() {
			giverhubSuccess['facebook-share']();
		});
	} catch(e) {
		giverhubError({e:e});
	}
});

function giverhubError(options) {
	if (!options) {
		options = {}
	}

	var subject = jQuery('#giverhub-error-modal-subject');
	subject.html(options.subject ? options.subject : subject.data('default-subject'));

	var msg = jQuery('#giverhub-error-modal-msg');
	msg.html(options.msg ? options.msg : msg.data('default-msg'));

	var $giverhubErrorModal = jQuery('#giverhub-error-modal');

	if (!GIVERHUB_LIVE || typeof(options.e) === "undefined" || typeof(window.first_js_error) === "undefined") {
		$giverhubErrorModal.modal('show');
		if (GIVERHUB_LIVE && typeof(options.e) !== "undefined") {
			window.first_js_error = true;
		}
	}

	if (GIVERHUB_DEBUG && options.e !== undefined && console !== undefined) {
		console.dir(options.e);
	}
	if (options.e !== undefined) {
		jQuery.ajax({
			url : '/home/js_exception',
			type : 'post',
			data : { e_msg : options.e.message, e_stack : options.e.stack, msg : options.msg, subject : options.subject }
		});
	}
}

jQuery(document).ready(function() {
	jQuery('#giverhub-error-modal').on('shown.bs.modal', function () {
		setTimeout(function(){
			jQuery('#giverhub-error-modal').find('.btn-close').focus();
		},1);
	});

	window.$promptModal = jQuery('#giverhub-prompt-modal');
	window.$promptModal.$msg = window.$promptModal.find('.msg');

	window.$promptModal.on('click', '.btn-yes', function() {
		try {
			window.$promptModal.options.yes();
		} catch(e) {
			giverhubError({e:e});
		}
		window.$promptModal.modal('hide');
	});

	window.$promptModal.on('click', '.btn-no', function() {
		try {
			window.$promptModal.options.no();
		} catch(e) {
			giverhubError({e:e});
		}
		window.$promptModal.modal('hide');
	});

	window.giverhubPrompt = function(options) {
		if (typeof(options) !== "object") {
			options = {};
		}
		if (typeof(options.yes) !== "function") {
			options.yes = function() {};
		}
		if (typeof(options.no) !== "function") {
			options.no = function() {};
		}
		if (typeof(options.msg) !== "string") {
			options.msg = "Are you sure?"
		}
		window.$promptModal.$msg.html(options.msg);
		window.$promptModal.options = options;
		window.$promptModal.modal('show');
	};

});

window.onerror = function(m,u,l){
	var data = {};
	try {
		data.window = window.location.href;
		data.msg = m;
		data.url = u;
		data.line = l;
	} catch(e) {}

	if (typeof(data.msg) === 'string' && data.msg.indexOf('NS_ERROR_FAILURE') === -1) {
		jQuery.post("/home/js_exception", data);
	}

	return true;
};

window.signInOrJoinFirst = function(msg) {
	if (typeof(msg) !== 'string' || !msg.length) {
		msg = 'Sign in or join first';
	}
	var sign_in_or_join_first_modal_2 = jQuery('#sign-in-or-join-first-modal-2');
	sign_in_or_join_first_modal_2.find('.alert-danger').html(msg);
	sign_in_or_join_first_modal_2.modal('show');
};

window.checkSuccess = function(json) {
	if (typeof(json) !== "object" || typeof(json.success) !== "boolean" || !json.success) {
		if (typeof(json.msg) === "string") {
			throw json.msg;
		}
		throw "Bad json response from server.";
	}
};

jQuery(document).ready(function () {

	jQuery(document).on('click', '[data-sign-in-or-join-first]', function() {
		window.signInOrJoinFirst(jQuery(this).data('data-sign-in-or-join-first'));
		return false;
	});

	jQuery('#giverhub-error-modal').on('hidden.bs.modal', function() {
		if (giverhubError.hideEvent !== undefined) {
			giverhubError.hideEvent();
			giverhubError.hideEvent = undefined;
		}
		return true;
	});

	jQuery('#giverhub-success-modal').on('hidden.bs.modal', function() {
		if (giverhubSuccess.hideEvent !== undefined) {
			giverhubSuccess.hideEvent();
			giverhubSuccess.hideEvent = undefined;
		}
		return true;
	});


	/*-----------------------------------*/
	/*	POPOVERS  */
	/*-----------------------------------*/
	var init_ttip_attempts_left = 6;
	function init_ttip() {
		var $ttip = jQuery('.ttip');

		if (typeof($ttip.popover) === "function") {
			$ttip
				.popover()
				.click(function () {
					return false;
				});

			jQuery('.gh_popover').popover();
			jQuery('.bs3_popover').popover();
			jQuery('.gh_tooltip').tooltip();
			jQuery('.btn-feedback').tooltip();

		} else {
			if (init_ttip_attempts_left) {
				setTimeout(init_ttip, 1000);
			}
			init_ttip_attempts_left--;
		}
	};
	init_ttip();


	/*-----------------------------------*/
	/*  RATING  */
	/*-----------------------------------*/
	$('.rating.rate a').mouseover(function () {
		if (!$(this).hasClass('voted')) {
			var i = $(this).index();
			$('.rating.rate a:lt(' + i + ')').addClass('over');
		}
	});

	$('.rating.rate a').mouseout(function () {
		$('.rating.rate a').removeClass('over');
	});

	$('.rating.rate a').click(function () {
		var signedIn = requireSignedIn();
		if (!signedIn) {
			return false;
		}
		var i = $(this).index();
		$('.rating.rate a').removeClass('voted');
		$(this).addClass('voted');
		$('.rating.rate a:lt(' + i + ')').addClass('voted');
		return false;
	});


	/*-----------------------------------*/
	/*  PLACEHOLDER  */
	/*-----------------------------------*/
	var test = document.createElement('input');
	if (!('placeholder' in test)) {
		$('input').each(function () {
			if ($(this).attr('placeholder') != "" && this.value == "") {
				$(this).val($(this).attr('placeholder'))
					.css('color', 'grey')
					.on({
						focus : function () {
							if (this.value == $(this).attr('placeholder')) {
								$(this).val("").css('color', '#000');
							}
						},
						blur : function () {
							if (this.value == "") {
								$(this).val($(this).attr('placeholder'))
									.css('color', 'grey');
							}
						}
					});
			}
		});
	}

	jQuery(document).on('click', '.view_tab', function() {
		var $this = jQuery(this);
		var viewId = $this.data('tabs-target');
		jQuery('.tabs-nav-ul').addClass('hide');
		jQuery(viewId).removeClass('hide');
	});

});//end


//the below js codes were in header.php -- to signin facebook, to check is login etc...

// facebook login
function fbjslogin(redirectUrl) {

	if (fbInitHasBeenRun) {

		FB.getLoginStatus(function (response) {
			if (response.status === 'connected') {
				fbLoggedIn(response.authResponse.accessToken, redirectUrl);
				// connected
			} else {
				FB.login(function (response) {
					if (GIVERHUB_DEBUG) {
						console.dir(response);
					}
					if (response.authResponse.accessToken) {
						fbLoggedIn(response.authResponse.accessToken, redirectUrl);
					} else {
						// cancelled
					}
				}, {scope: 'public_profile,email,user_friends'});
			}
		});

	} else {
		runLoginAfterFbInit = redirectUrl;
	}
}

jQuery(document).ready(function() {
	if (location.href.search('xdebugx') != -1) {
		alert('debug!');
	}
});

function fbLoggedIn(accessToken, redirectUrl) {
	$body.addClass('fb-loading');

	if (location.href.search('xdebugx') != -1) {
		alert(accessToken + ':' + redirectUrl);
	}

	jQuery.ajax({
		url : '/home/fbLogin',
		type : 'post',
		dataType : 'json',
		data : {
			fbAccessToken : accessToken
		},
		complete : function() {
			if (location.href.search('xdebugx') != -1) {
				alert('complete');
			}
		},
		error : function(a,b,c) {
			if (location.href.search('xdebugx') != -1) {
				alert('a: ' + a + ' b: ' + b + ' c: ' + c + ' a-s: ' + JSON.stringify(a) + ' b-s: ' + JSON.stringify(b) + ' c-s: ' + JSON.stringify(c));
			}
		},
		success : function (json) {

			if (location.href.search('xdebugx') != -1) {
				alert('json: ' + json + ' string: ' + JSON.stringify(json));
			}

			var msg;

			if (typeof(json) === "object" && typeof(json.msg) === "string") {
				msg = json.msg;
			}
			switch (msg) {
				case 'success':
				case 'success-admin':
					if (GIVERHUB_LIVE) {
						ga('send', 'event', 'signin', 'facebook');
						ga('send', 'pageview', '/virtual/signin/facebook');
					}
					if (!redirectUrl && typeof(json.charity_admin_url) === "string") {
						redirectUrl = json.charity_admin_url;
					}
					window.location.href = redirectUrl ? redirectUrl : '/';
					break;
				case 'failed':
					$body.removeClass('fb-loading');
					alert('Logging in through facebook failed at this time.');
					console.dir(json);
					break;
				case 'not-found':
					$body.removeClass('fb-loading');
					$(".modal").modal("hide");
					$("#signup-modal-facebook").modal('show');
					break;
				case 'already':
					$body.removeClass('fb-loading');
					break;
				case 'no-email-username':
					$body.removeClass('fb-loading');
					giverhubError({msg : 'Oops, We have not been able to get your email address from facebook. This probably means that you have not confirmed your email address with facebook. For security reasons we cannot allow you to sign up using facebook in this case.'});
					break;
				default:
					$body.removeClass('fb-loading');
					giverhubError({msg : 'Oops, something went wrong with Facebook sign in. You can manually sign in below or attempt signing in with Facebook using another web browser (Mozilla, Chrome, Safari).'});
					break;
			}
		}
	});
}


function save_facebook_like(like) {
	try {
		if (GIVERHUB_DEBUG) {
			console.log('save_fb_like ' + like);
		}
		if (body.data('signed-in') && body.data('charity-id')) {
			var charityId = body.data('charity-id');

			jQuery.ajax({
				url : '/home/save_facebook_like',
				type : 'post',
				dataType : 'json',
				data : { charityId : charityId, like : like },
				error : function() {
					giverhubError({msg : 'Something went wrong when saving your like to giverhub.'});
				},
				success : function(json) {
					try {
						if (json === undefined || !json || json.success === undefined || !json.success) {
							giverhubError({msg : 'Bad response from server when saving your like to giverhub.'});
						}
					} catch(e) {
						giverhubError({e:e});
					}
				}
			});
		}
	} catch(e) {
		giverhubError({e:e});
	}
}


var fbInitHasBeenRun = false;
var runLoginAfterFbInit = false;


window.fbAsyncInit = function () {
	try {
		var checkAttempts = 0;
		function checkAppId() {
			try {
				if (typeof appId === 'undefined') {
					checkAttempts++;
					if (checkAttempts < 20) {
						setTimeout(checkAppId, 500);
					} else {
						throw "checked for appId "+checkAttempts+" times.";
					}
				} else {
					// https://developers.facebook.com/docs/javascript/reference/FB.init/v2.0
					FB.init({
						appId : appId,
						channelUrl : channelUrl,
						status : true, // check login status
						cookie : true, // enable cookies to allow the server to access the session
						xfbml : true,  // parse XFBML
						version : 'v2.2' // new required parameter.
					});

					fbInitHasBeenRun = true;
					if (runLoginAfterFbInit !== false) {
						fbjslogin(runLoginAfterFbInit);
					}

					FB.Event.subscribe('edge.create', function (response,b) {
						var btn = jQuery('.btn-follow-charity');
						var action = btn.data('action');
						if (action == "follow") {
							btn.trigger('click');
						}
						save_facebook_like(true);
					});

					FB.Event.subscribe('edge.remove', function (response,b) {
						save_facebook_like(false);
					});
				}
			} catch(e) {
				giverhubError({e:e});
			}
		}
		if (!$body.hasClass('embedded-vol-cal')) {
			checkAppId();
		}
	} catch(e) {
		giverhubError({e:e});
	}
};


function facebook_sign_in(obj) {
	var url = String(obj);
	url = url.replace("#", '');
	url = url == body.data('base-url') ? false : url;


	if (window.location.search.length) {
		var matches = window.location.search.match('\\?redirect=(.*)');
		if (matches && matches[1].length) {
			url = body.data('base-url') + matches[1].substring(1);
		}
	}

	fbjslogin(url);
	return false;
}

(function (d) {
	var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
	if (d.getElementById(id)) {
		return;
	}
	js = d.createElement('script');
	js.id = id;
	js.async = true;
	js.src = "//connect.facebook.net/en_US/sdk.js";//jsSource;
	ref.parentNode.insertBefore(js, ref);
}(document));

jQuery(document).ready(function () {

	var init_elastic_attempts_left = 6;
	var $activity_feed_textarea = jQuery('.activity-feed-textarea');
	function init_elastic() {
		var $activity_feed_textarea = jQuery('.activity-feed-textarea');
		if (typeof($activity_feed_textarea.elastic) === "function") {
			$activity_feed_textarea.elastic();
		} else {
			if (init_elastic_attempts_left) {
				setTimeout(init_elastic, 1000);
			}
			init_elastic_attempts_left--;
		}
	}

	if ($activity_feed_textarea.length) {
		init_elastic();
	}

	jQuery(document).on('click', '.facebook-sign-in', function () {
		facebook_sign_in(this);
		return false;
	});

	jQuery(document).on('click', '.google-sign-in', function () {
		try {
			var $this = jQuery(this);
			if (window.location.search.length) {
				var matches = window.location.search.match('\\?redirect=(.*)');
				if (matches && matches[1].length) {
					jQuery.ajax({
						url : '/home/get_google_url',
						type : 'get',
						dataType : 'json',
						data : { redirect : matches[1] },
						error : function() {
							window.location = $this.attr('href');
						},
						success : function(json) {
							if (!json || json === undefined || json.success === undefined || !json.success || json.url === undefined) {
								window.location = $this.attr('href');
							} else {
								window.location = json.url;
							}
						}
					});
					return false;
				}
			}
		} catch(e) {
			giverhubError({e:e});
		}
		return true;
	});


	jQuery(document).on('keydown', '.sign_username', function (event) {
		try {
			if (event.keyCode == 13) {
				var $this = jQuery(this);
				jQuery($this.data('signin-button')).click();
			}
		} catch(e) {
			giverhubError({e:e});
		}
	});

	jQuery(document).on('keydown', '.sign_pass', function (event) {
		try {
			if (event.keyCode == 13) {
				var $this = jQuery(this);
				jQuery($this.data('signin-button')).click();
			}
		} catch(e) {
			giverhubError({e:e});
		}
	});

	jQuery(document).on('click', '.btn-submit-login', function () {
		try {
			var signinBtn = jQuery(this);

			var $input_container = jQuery(signinBtn.data('input-container'));

			var $username = $input_container.find('.sign_username');
			var $password = $input_container.find('.sign_pass');

			var address = '/home/validate_credentials/';

			var username = $username.val();
			var password = $password.val();

			var redirect = window.location.href;

			redirect = redirect == body.data('base-url') ? false : redirect;

			if (window.location.search.length) {
				var matches = window.location.search.match('\\?redirect=(.*)');
				if (matches && matches[1].length) {
					redirect = matches[1];
				}
			}


			if (username == "" || password == "") {
				giverhubError({msg : 'Enter username and password please.'});
				return false;
			}

			signinBtn.button('loading'); //Change button text to loading when trying signing in

			jQuery.post(address, {
				username : username,
				password : password
			}, function (json) {
				if (json.level >= 2) {
					$body.addClass('fb-loading');

					if (!redirect && typeof(json.charity_admin_url) === "string") {
						redirect = json.charity_admin_url;
					}
					window.location.href = redirect ? redirect : '/';

					if (GIVERHUB_LIVE) {
						ga('send', 'event', 'signin', 'manual');
						ga('send', 'pageview', '/virtual/signin/manual');
					}
				} else if (json.level == 1) {
					signinBtn.button('reset');
					giverhubError({msg : 'Your account has not been confirmed yet.'});
				} else {
					signinBtn.button('reset');
					giverhubError({msg : 'Invalid username / password.'});
				}
			}, 'json');
		} catch(e) {
			giverhubError({e:e});
		}
		return false;
	});

	jQuery(document).on('click', '.btn-submit-signup', function () {
		var submitButton = jQuery(this);

		var $targetForm = jQuery(submitButton.data('target-form'));

		submitButton.button('loading');
		jQuery('.signup-message-container').addClass('hide');
		jQuery.post('/register/signup', $targetForm.serialize(), function (json) {
			if (json.success) {
				if (submitButton.data('signup-message') == 'modal') {
					giverhubSuccess({subject : 'Great!', msg : 'an activation email has been sent to you! Please check spam/trash folder too.'});
				} else {
					jQuery('.signup-message').html('Great, an activation email has been sent to you! Please check spam/trash folder too.');
					jQuery('.signup-message-container').addClass('alert-success').removeClass('alert-danger').removeClass('hide');
				}
				if (GIVERHUB_LIVE) {
					ga('send', 'event', 'signup', 'manual');
					ga('send', 'pageview', '/virtual/signup/manual');
				}
				if (GIVERHUB_DEBUG) {
					console.log('signed up.');
				}
			} else {
				if (submitButton.data('signup-message') == 'modal') {
					giverhubError({subject : '', msg : json.msg});
				} else {
					jQuery('.signup-message').html(json.msg);
					jQuery('.signup-message-container').addClass('alert-danger').removeClass('alert-success').removeClass('hide');
				}
			}
			submitButton.button('reset');

		}, 'json');
		return false;
	});

	jQuery(document).on('click', '.btn-feedback', function() {
		try {
			jQuery('#feedback-textarea').val('');
			jQuery('#feedback-modal').modal('show');
		} catch(e) {
			giverhubError({e:e});
		}
		return false;
	});

	jQuery(document).on('click', '.btn-send-feedback', function() {
		var btn = jQuery(this);
		try {
			btn.button('loading');

			var text = jQuery('#feedback-textarea').val();

			if (text.length < 5) {
				giverhubError({subject : 'Message is too short.', msg : 'You need to type at least 5 characters.'});
				btn.button('reset');
				return false;
			}
			jQuery.ajax({
				url : '/home/feedback',
				type : 'post',
				dataType : 'json',
				data : {
					text : text,
					url : currentUrl
				},
				error : function() {
					giverhubError({msg : 'This is really embarrassing, something went wrong when processing your feedback. Please try again or send an email to admin@giverhub.com'});
				},
				success : function(json) {
					try {
						if (json === undefined || !json || json.success === undefined || !json.success) {
							giverhubError({msg : 'This is really embarrassing, something went wrong when processing your feedback. Please try again or send an email to admin@giverhub.com'})
						} else {
							giverhubSuccess({msg : 'Thank you for leaving feedback! Your feedback is extremely valuable for us!'});
							jQuery('#feedback-textarea').val('');
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

	if (body.data('flash-error')) {
		giverhubError({subject: '.', msg : body.data('flash-error') });
	}


	jQuery(".nav li a").each(function() {
		if (jQuery(this).next().length > 0) {
			jQuery(this).addClass("parent");
		}
	});

	var $toggleMenu = jQuery(".toggleMenu");
	$toggleMenu.click(function(e) {
		e.preventDefault();
		jQuery(this).toggleClass("active");
		jQuery(".nav").toggle();
	});

	jQuery(window).resize(function() {
		if (jQuery(window).width() > 768) {
			jQuery(".nav").removeAttr('style');
		}
	});

	/* Added code for toggle the menu using list-inline class, to fix Mantis Issue regarding Horizontal responsive menu */
	$toggleMenu.click(function(e) {
		e.preventDefault();
		jQuery(this).toggleClass("active");
		jQuery(".list-inline").toggle().css({ marginTop:"15px" });
	});

	jQuery(window).resize(function() {
		if (jQuery(window).width() > 768) {
			jQuery(".list-inline").removeAttr('style');
		}
	});


	if (body.data('signed-in')) {
		jQuery(document).ajaxComplete(function(event, xhr, settings) {
			try {
				if (typeof(window.logging_out) === 'boolean' && window.logging_out) {
					return false;
				}
				if (settings.url != '/home/check_logged_in') {
					jQuery.ajax({
						url : '/home/check_logged_in',
						type : 'get',
						dataType : 'json',
						success : function(json) {
							try {
								if (json === undefined || !json || json.success === undefined || !json.success || json.signed_in === undefined) {
								} else {
									if (!json.signed_in) {
										giverhubError({msg : 'You have been signed out.'});

										jQuery('#sign-in-modal-2').modal('show').on('hide.bs.modal', function () {
											return false;
										});
									}
								}
							} catch(e) {
							}
						}
					});
				}
			} catch(e) {
				giverhubError({e:e});
			}
		});
	}

	jQuery(document).ajaxComplete(function() {
		setTimeout(function() {
			jQuery('.gh_tooltip').each(function(i,tooltip) {
				jQuery(tooltip).tooltip();
			});
		},1000);
	});

	var tour_taken = function(success) {
		jQuery.ajax({
			url : '/home/dashboard_tour',
			type : 'post',
			dataType : 'html',
			data : { tour_taken : "1" },
			error : function() {
				giverhubError({msg : 'Request Failed. Please try again later. Thank you for being patient.'})
			},
			success : function(data) {
				if (typeof(success) === "function") {
					success();
				}
			}
		});
	};

	var $joyridetipcontent = jQuery('#joyRideTipContent');
	if ($joyridetipcontent.length) {
		//Code added to display dashboard tour tooltips
		$joyridetipcontent.joyride({
			autoStart : true,
			postStepCallback : function (index, tip) {
				if (index == 5) {
					$(this).joyride('set_li', false, 1);

					tour_taken(function() {});
				}
			},
			postRideCallback :function () {
				tour_taken(function() { jQuery('.joyride-tip-guide').css('display','none'); });
			},
			modal:true,
			expose: true
		});
	}

	if (body.data('missing-name')) {
		var $missingNameModal = jQuery('#missing-name-modal');
		$missingNameModal.modal('show').on('hide.bs.modal', function () {
			giverhubError({subject : 'I\'m sorry, Dave.', msg : 'I\'m afraid I can\'t let you do that.'});
			return false;
		});

		$missingNameModal.on('click', '.btn-save-missing-name', function() {
			var $btn = jQuery(this);
			try {
				$btn.button('loading');

				var f_name = jQuery('#missing_first_name').val().trim();
				var l_name = jQuery('#missing_last_name').val().trim();

				if (!f_name.length) {
					$btn.button('reset');
					giverhubError({msg : 'You need to enter first name.'});
					return false;
				}
				if (!l_name.length) {
					$btn.button('reset');
					giverhubError({msg : 'You need to enter last name.'});
					return false;
				}

				jQuery.ajax({
					url : '/home/save_missing_name',
					type : 'post',
					dataType : 'json',
					data : { l_name : l_name, f_name : f_name },
					error : function() {
						giverhubError({msg : 'Request Failed. Please try again later. Thank you for being patient.'})
					},
					success : function(json) {
						try {
							if (!json || json === undefined || json.success === undefined || !json.success) {
								giverhubError({msg : 'Bad response. Sorry, please try again later.'});
							} else {
								$missingNameModal.off('hide.bs.modal').modal('hide');
								body.data('missing-name', 0);
								giverhubSuccess({subject : 'Thank you!', msg : 'Your name has been saved!'});
							}
						} catch(e) {
							giverhubError({msg : 'Invalid response from server. Sorry, please try again later.'})
						}
					},
					complete : function() {
						$btn.button('reset');
					}
				});

			} catch(e) {
				$btn.button('reset');
				giverhubError({e:e});
			}

			return false;
		});
	}

	/*
	 * sorry neetu, disabled this on Andrews request, my bad!
	 * http://bugtracker.giverhub.com/view.php?id=807
	 */
	if (false && body.data('choose-username')) {
		var $chooseUsernameModel = jQuery('#choose-username-modal');
		$chooseUsernameModel.modal('show').on('hide.bs.modal', function () {
			setCookie("remind_later_username","remind_later");
		});

		$chooseUsernameModel.on('click', '#remind_username_later', function() {		
			setCookie("remind_later_username","remind_later");
			//jQuery('#choose-username-modal').hide();
			jQuery('#choose-username-modal').modal('hide');
		});

		jQuery('.btn-save-username').on('click', function () {
			var submitButton = jQuery(this);
			submitButton.button('loading');
			jQuery.post('/home/save_new_username', jQuery('#frm-change-username').serialize(), function (json) {
				if (json.success) {
					jQuery('#update-username-message').html('Thankyou! You have Sucessfully updated your Username.');
				} else {
					jQuery('#update-username-message').html(json.msg);
					jQuery('#update-username-message-container').addClass('alert-danger').show();
					jQuery('#update-username-message-container').removeClass('alert-success').show();
				}
				submitButton.button('reset');
			}, 'json');
			return false;
		});

		function setCookie(name,value) {
			var date = new Date();
			date.setTime(date.getTime()+(60*5000));
			var expires = "expires="+date.toGMTString();
			document.cookie = name+"="+value+"; "+expires;
		}
	}

	jQuery(document).on('click', '.take-a-tour-btn', function() {
		//alert("test");return false;
		var redirect = window.location.href;
		window.location.href = '/tour/';
	});

	jQuery(document).on('click', '.landing-page-bet-friend-modal', function() {
		try {
			jQuery('#learn-about-bet-modal').modal('show');
		} catch(e) {
			giverhubError({e:e});
		}
		return false;
	});


	var $gh_header = jQuery('.gh_header');
	var $main_content_area = jQuery("main");
	var $new_navigation_menu = jQuery("#new_navigation_menu");
	var $new_nav_ul_list_inline_li_a = jQuery('.new-nav-ul-list-inline > li > a');
	var $list_inline = jQuery('.list-inline');
	var $new_dashboard_icon_new_settings_icon = jQuery('.new-dashboard-icon, .new-settings-icon');
	var $old_dashboard_icon_old_settings_icon = jQuery('.old-dashboard-icon, .old-settings-icon');
	var $navbar_take_tour_link = jQuery('.navbar-take-tour-link > a');
	var $new_nav_search_input_form_control = jQuery('.new-nav-bar-search .input-group .form-control');
	var $new_nav_ul_list_inline = jQuery('.new-nav-ul-list-inline');

	if (false && !$main_content_area.hasClass('no-scroll-navbar') && jQuery(window).width() > 992) {
		jQuery(window).scroll(function() {
			try {
				if ($main_content_area.length && $new_navigation_menu.length) {
					var main_content_area_top = $main_content_area.offset().top;
					var new_navigation_menu_top = $new_navigation_menu.offset().top;
					var main_content_area_bottom = main_content_area_top + $main_content_area.height();
					var new_navigation_menu_bottom = new_navigation_menu_top + $new_navigation_menu.height();

					if (main_content_area_bottom >= new_navigation_menu_top && main_content_area_top < new_navigation_menu_bottom) {
						$gh_header.css('background', 'none repeat scroll 0 0 rgba(0, 0, 0, 0.61)');
						$gh_header.css('background-repeat', 'repeat-x');
					} else {
						$gh_header.css('background', 'none repeat scroll 0 0 rgba(255, 255, 255, 0.27)');
					}
				}
			} catch(e) {
				giverhubError({e:e});
			}
		});
	}

	//Code to redirect Nonprofits and Petition while clicked from members page
	jQuery(document).on('click', '.members_new_nav_nonprofits', function() {
		try {
			jQuery("#members_new_nav_nonprofits_view").submit();
		} catch(e) {
			giverhubError({e:e});
		}
		return false;
	});

	jQuery(document).on('click', '.members_new_nav_petitions', function() {
		try {
			jQuery("#members_new_nav_petitions_view").submit();
		} catch(e) {
			giverhubError({e:e});
		}
		return false;
	});



	/*
	var $intro_vid_modal = jQuery('#introduction-video-modal');
	if ($intro_vid_modal.length && !jQuery.browser.mobile && !jQuery.cookie('seen-welcome-vid')) {
		jQuery.cookie('seen-welcome-vid', true, { expires: 365, path: '/' });
		var tag = document.createElement('script');

		tag.src = "https://www.youtube.com/iframe_api";
		var firstScriptTag = document.getElementsByTagName('script')[0];
		firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

		// 3. This function creates an <iframe> (and YouTube player)
		//    after the API code downloads.
		var player;
		window.onYouTubeIframeAPIReady = function() {
			player = new YT.Player('introduction-video-div', {
				height: '390',
				width: '640',
				videoId: 'gyZXGfkheYU',
				events: {
					'onReady': onPlayerReady
				}
			});
		};

		// 4. The API will call this function when the video player is ready.
		function onPlayerReady(event) {
			event.target.playVideo();
		}

		function stopVideo() {
			if (typeof(player) === 'object' && typeof(player.stopVideo) === "function") {
				player.stopVideo();
			} else {
				setTimeout(stopVideo, 500);
			}
		}
		$intro_vid_modal.on('hidden.bs.modal', function() {
			stopVideo();
			return true;
		});

		$intro_vid_modal.modal('show');
	}
	*/


	jQuery(document).on('click', '.logout-link', function() {
		try {
			window.logging_out = true;
		} catch(e) {
			giverhubError({e:e});
		}
		return true;
	});

	jQuery(document).ready(function() {
		jQuery('#admin-toolbar-close').click(function() {
			jQuery('#admin-toolbar').addClass('hide');
			return false;
		});
	});
});

