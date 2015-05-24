jQuery(document).ready(function() {

	jQuery(document).on('click', '.gmail-invite', function() {
		var btn = jQuery(this);
		var email = btn.data('address');

		jQuery.post("/home/send_invitation_gmail", {email : email}, function(data){
			if(data=="success"){
				giverhubSuccess({
					subject : 'Success!',
					msg : 'Invitation Email sent to ' + email
				});
				btn.addClass('selected');
			} else {
				giverhubError();
			}
		}, 'text');

		return false;
	});

	jQuery(document).on('click', '.btn-follow-selected', function () {
		var btn = jQuery(this);
		btn.button('loading');

		var userIds = [];
		jQuery('.follow_checkbox:checked').each(function(i,e) {
			var $e = jQuery(e);
			userIds.push($e.data('user-id'));
		});

		if (!userIds.length) {
			giverhubError({msg: 'Select some users first.'});
			btn.button('reset');
			return false;
		}

		jQuery.ajax({
			url : '/home/followSelected',
			type : 'post',
			data : { userIds : userIds.join(',') },
			dataType : 'json',
			error : function() {
				giverhubError({msg : 'Request failed.'});
				btn.button('reset');
			},
			success : function(json) {
				if (!json || json===undefined || json.success===undefined || !json.success) {
					giverhubError({msg : 'Bad response from server.'});
				}
				location.reload();
			}
 		});
		return false;
	});

	function fbInvite(fbId, dataLink, callback) {
		if (GIVERHUB_DEBUG) {
			console.dir(dataLink);
		}

		var picture = "https://graph.facebook.com/" + $("#fb_user_id").val() + "/picture";

		jQuery.post('/home/fb_profile_image/', {
			fbId : fbId,
			pro_image_url : picture
		}, function () {
		}, 'text');

		FB.ui({
			method : 'send',
			to : fbId,
			link : dataLink
		}, callback);
	}

	jQuery(document).on('click', '.fb-invite', function () {
		var btn = jQuery(this);
		fbInvite(
			parseInt(btn.data('fb-id')),
			btn.data('link'),
			function (response) {
				if (response == null || !response.success) {
					jQuery('#alert-fb-invite-message').html('There was a problem sending the friend request(s), please try again later.');
					jQuery('#alert-fb-invite').show();

					jQuery.post(
						'/home/fb_profile_image_delete/',
						{ fbId : btn.data('fb-id') },
						function (data) {},
						'text'
					);
					return;
				}

				jQuery.post(
					'/home/fb_profile_image_delete/',
					{ fbId : btn.data('fb-id'), success : 1 },
					function (data) {
						if (data == 'success') {
							btn.addClass('selected').removeClass('fb-invite');
						}
					},
					'text'
				);

				btn.addClass('selected');
			}
		);
		return false;
	});

	/*
		NOTE that this is used in both find friends page AND on charity-followers page.
	 */
	jQuery(document).on('click', '.btn-follow-user', function () {
		var btn = jQuery(this);

		var charityFollowersPage = btn.hasClass('charity-followers');

		if (charityFollowersPage) {
			btn.button('loading');
		}
		jQuery.ajax({
			url : '/members/toggleFollowUser',
			type : 'post',
			data : { userId : btn.data('user-id') },
			dataType : 'json',
			error : function() {
				giverhubError({msg : 'Request failed.'});
			},
			success : function(json) {
				try {
					if (!json || json===undefined || json.success===undefined || !json.success || json.following === undefined) {
						giverhubError({msg : 'Bad response from server.'});
					}
					var doWhenFollowing;
					var doWhenNotFollowing;
					if (charityFollowersPage) {
						var btnContainer = btn.closest('.btn-follow-user-container');
						var followingBtn = btnContainer.find('.btn-follow-user.following');
						var notFollowingBtn = btnContainer.find('.btn-follow-user.not-following');

						doWhenFollowing = function() {
							notFollowingBtn.addClass('hide');
							followingBtn.removeClass('hide');
						};

						doWhenNotFollowing = function() {
							followingBtn.addClass('hide');
							notFollowingBtn.removeClass('hide');
						};

					} else {
						doWhenFollowing = function() {
							btn.addClass('selected');
						};
						doWhenNotFollowing = function() {
							btn.removeClass('selected');
						}
					}
					if (json.following) {
						doWhenFollowing();
					} else {
						doWhenNotFollowing();
					}
				} catch(e) {
					giverhubError({e:e});
				}
			},
			complete : function() {
				try {
					if (charityFollowersPage) {
						btn.button('reset');
					}
				} catch(e) {
					giverhubError({e:e});
				}
			}
		});

		return false;
	});

	var searchFieldInviteFriends = jQuery('#search-field-invite-friends');
	var searchableFriendItems = jQuery('.searchable-friend-item');
	var searchPrev = searchFieldInviteFriends.val();


	var searchFieldFollowFriends = jQuery("#search-field-follow-friends");
	var searchableFollowFriendItems = jQuery(".searchable-follow-friend-item");
	var searchFollowPrev = searchFieldFollowFriends.val();

	var i = setInterval(function () {
		var re;
		var val = searchFieldInviteFriends.val();
		if (val != searchPrev) {
			searchPrev = val;
			re = new RegExp(val, "i");
			searchableFriendItems.each(function (i, e) {
				e = jQuery(e);
				if (e.data('name').match(re)) {
					e.show();
				} else {
					e.hide();
				}
			});
		}
		//search follow friends
		val = searchFieldFollowFriends.val();
		if (val != searchFollowPrev) {
			searchFollowPrev = val;
			re = new RegExp(val, "i");
			searchableFollowFriendItems.each(function (i, e) {
				e = jQuery(e);
				if (e.data('name').match(re)) {
					e.show();
				} else {
					e.hide();
				}
			});
		}
	}, 100);
});
