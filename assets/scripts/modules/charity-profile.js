jQuery(document).ready(function () {
	try {
		jQuery(document).on('click', '.btn-follow-charity', function () {
			try {
				var btn = jQuery(this);
				btn.attr('disabled', 'disabled');
				var action = btn.data('action');
				var charityId = btn.data('charity-id');
				jQuery.ajax({
					url : '/charity/follow',
					data : {
						action : action,
						id : charityId
					},
					error : function () {
						giverhubError({msg : 'Request Failed.'});
					},
					complete : function () {
						btn.removeAttr('disabled');
					},
					success : function (json) {
						try {
							if (json === undefined || !json || json.success === undefined || !json.success || json.action === undefined) {
								giverhubError({msg : 'Bad response from server.'});
							} else {
								var newAction = '';
								switch (json.action) {
									case 'follow':
										newAction = 'unfollow';
										btn.removeClass('btn-primary');
										btn.addClass('btn-warning');
										break;
									case 'unfollow':
										newAction = 'follow';
										btn.removeClass('btn-warning');
										btn.addClass('btn-primary');
										break;
									default:
										giverhubError('Bad action response from server.');
								}
								btn.data('action', newAction);
								btn.html(newAction.toUpperCase());
							}
						} catch (e) {
							giverhubError({e : e});
						}
					},
					dataType : 'json',
					type : 'post'
				});
			} catch (e) {
				giverhubError({e : e});
			}
		});

		jQuery(document).on('click', '.btn-add-keyword', function () {
			var keywordInput = jQuery('#add-keyword-keyword');
			keywordInput.val('');
			jQuery('#add-keyword-modal').modal('show');
			keywordInput.focus();
		});


		function keywordPopover() {
			jQuery('.charity-header-keyword').each(function(i, e) {
				var content =
					'<a href="#" data-keyword-id="'+ jQuery(e).data('keyword-id') +'" class="btn btn-primary btn-keyword-up-vote" role="button" data-toggle="popover">' +
						'<i class="glyphicon glyphicon-thumbs-up"></i>' +
					'</a>' +
					'<span class="badge">' + jQuery(e).data('keyword-up-votes') + '</span>' +


					'<a href="#" data-keyword-id="'+ jQuery(e).data('keyword-id') +'" class="btn btn-default btn-keyword-down-vote" role="button">' +
						'<i class="glyphicon glyphicon-thumbs-down"></i>' +
					'</a>' +
					'<span class="badge">' + jQuery(e).data('keyword-down-votes') + '</span>' +


					'<a href="#" data-keyword-id="'+ jQuery(e).data('keyword-id') +'" class="btn btn-success btn-keyword-flag" role="button">' +
						'<i class="glyphicon glyphicon-flag"></i>' +
					'</a>' +
					'<span class="badge">' + jQuery(e).data('keyword-flagged') + '</span>';

				var originalLeave = $.fn.popover.Constructor.prototype.leave;
				jQuery.fn.popover.Constructor.prototype.leave = function(obj){
					var self = obj instanceof this.constructor ?
						obj : jQuery(obj.currentTarget)[this.type](this.getDelegateOptions()).data('bs.' + this.type)
					var container, timeout;

					originalLeave.call(this, obj);

					if(obj.currentTarget) {
						container = jQuery(obj.currentTarget).siblings('.popover')
						timeout = self.timeout;
						container.one('mouseenter', function(){
							clearTimeout(timeout);
							container.one('mouseleave', function(){
								jQuery.fn.popover.Constructor.prototype.leave.call(self, self);
							});
						})
					}
				};

				jQuery(e).popover({
					html: true,
					placement: 'bottom',
					content: content,
					trigger:'hover',
					delay: {show: 100, hide: 50}
				});


			});
		}
		keywordPopover();


		jQuery(document).on('click', '.btn-keyword-up-vote, .btn-keyword-down-vote', function() {
			if (!$body.data('signed-in')) {
				jQuery('#signin-or-join-first-modal').modal('show');
				return false;
			}
			var btn = jQuery(this);
			try {
				btn.attr('disabled', 'disabled');
				var vote = btn.hasClass('btn-keyword-up-vote') ? 1 : -1;
				var keyword_id = btn.data('keyword-id');
				jQuery.ajax({
					url : '/charity/keyword_vote',
					type : 'post',
					dataType : 'json',
					data : {
						vote : vote,
						keyword_id : keyword_id
					},
					error : function() {
						giverhubError({msg : 'Request failed.'});
						btn.removeAttr('disabled');
					},
					success : function(json) {
						try {
							if (json === undefined || !json || json.success === undefined || !json.success || json.keywordsHtml === undefined) {
								giverhubError({msg : 'Bad response from server.'});
							} else {
								jQuery('#charity-keywords-container').html(json.keywordsHtml);
								keywordPopover();
								jQuery('#charity-keywords-container .popover').hide();
								giverhubSuccess({msg : 'Thank you for your help in making the site a better place.'});
							}
						} catch (e) {
							giverhubError({e : e});
						}
						btn.removeAttr('disabled');
					}
				});
			} catch(e) {
				giverhubError({e:e});
				btn.removeAttr('disabled');
			}
			return false;
		});

		jQuery(document).on('click', '.btn-keyword-flag', function() {
			if (!$body.data('signed-in')) {
				jQuery('#signin-or-join-first-modal').modal('show');
				return false;
			}

			var x = 'z';

			var btn = jQuery(this);
			try {
				btn.attr('disabled', 'disabled');
				var keyword_id = btn.data('keyword-id');
				jQuery.ajax({
					url : '/charity/keyword_flag',
					type : 'post',
					dataType : 'json',
					data : {
						keyword_id : keyword_id
					},
					error : function() {
						giverhubError({msg : 'Request failed.'});
						btn.removeAttr('disabled');
					},
					success : function(json) {
						try {
							if (json === undefined || !json || json.success === undefined || !json.success || json.keywordsHtml === undefined) {
								giverhubError({msg : 'Bad response from server.'});
							} else {
								jQuery('#charity-keywords-container').html(json.keywordsHtml);
								keywordPopover();
								jQuery('#charity-keywords-container .popover').hide();
								giverhubSuccess({msg : 'Thank you for your help in making the site a better place.'});
							}
						} catch (e) {
							giverhubError({e : e});
						}
						btn.removeAttr('disabled');
					}
				});
			} catch(e) {
				giverhubError({e:e});
				btn.removeAttr('disabled');
			}
			return false;
		});

		jQuery(document).on('click', '.btn-add-keyword-save', function () {
			var btn = jQuery(this);
			try {
				btn.button('loading');

				var charityId = btn.data('charity-id');
				var keywordInput = jQuery('#add-keyword-keyword');
				var keyword = keywordInput.val();
				if (!keyword) {
					giverhubError({subject : 'Information missing.', msg : 'Type a keyword first.'});
					keywordInput.focus();
					btn.button('reset');
					return false;
				}

				jQuery.ajax({
					url : '/charity/add_keyword',
					dataType : 'json',
					type : 'post',
					data : { keyword : keyword, charityId : charityId},
					error : function () {
						giverhubError({msg : 'Request Failed.'});
					},
					complete : function () {
						btn.button('reset');
					},
					success : function (json) {
						try {
							if (json === undefined || !json || json.success === undefined || !json.success || json.keywordsHtml === undefined) {
								if (json.success === false && json.message) {
									giverhubError({subject : 'There was a problem saving the keyword', msg : json.message});
								} else {
									giverhubError({msg : 'Bad response from server.'});
								}
							} else {
								jQuery('#charity-keywords-container').html(json.keywordsHtml);
								keywordPopover();
								jQuery('#add-keyword-modal').modal('hide');
							}
						} catch (e) {
							giverhubError({e : e});
						}
					}
				});
			} catch (e) {
				btn.button('reset');
				giverhubError({e : e});
			}
			return false;
		});


		/*
		 *  365 -
		 *
		 */
		jQuery('#review-message-container').hide();

		var $review_form = jQuery('#review-submit-form');

		$review_form.on('click', '#review-form-submit', function (e) {

			var signedIn = requireSignedIn();
			if (!signedIn) {
				return false;
			}

			e.preventDefault();
			jQuery('#review-message-container').hide();
			var form = jQuery('#review-submit-form');

			var rating = jQuery('.rate a.voted', form).length;
			//var review_desc = jQuery('textarea[name="review-desc"]').val();
			var review_desc = jQuery('#leave-review-textarea').code();

			var btn = jQuery(this);


			btn.button('loading');

			var data = {
				review_desc : review_desc,
				charity_id : jQuery('#feature-charity-modal').data('charity-id'),
				rating : rating
			};

			jQuery.ajax({
				url : '/charity/review_charity',
				type : 'post',
				data : data,
				dataType : 'json',
				error : function () {
					giverhubError({msg : 'Request failed.'});
					btn.button('reset');
				},
				success : function (json) {
					try {
						if (json === undefined || json.success === undefined || json.count === undefined || json.review === undefined) {
							giverhubError({msg : 'Bad response from server.'});
							btn.button('reset');
						} else {
							if (json.success) {
								jQuery('.charity-review-count').html(json.count);
								jQuery('#review-message').html('');
								jQuery('#review-message-container').removeClass('alert-danger').hide();
								giverhubSuccess({subject : 'Review saved!', msg : 'Thank you for leaving a review.'});
								jQuery('#leave-review-textarea').code('Thank you for your review!').attr('disabled', 'disabled');
								jQuery('#review-submit-form').find('.note-editable').attr('contenteditable', 'false');
								var $edit_btn = jQuery('.btn-edit-nonprofit-review');
								$edit_btn.data('current-review', data.review_desc);
								$edit_btn.data('current-rating', data.rating);
								$edit_btn.removeAttr('disabled');
								$edit_btn.removeClass('hide');
								var $review = jQuery(json.review);
								var $existing = jQuery('#'+$review.attr('id'));
								if ($existing.length) {
									$existing.remove();
								}

								jQuery('.charity-reviews-col1').prepend($review);
								btn.button('reset');
								setTimeout(function() {btn.attr('disabled','disabled');},0);
								$review_form.find('.btn-remove-nonprofit-review').removeClass('hide').data('review-info-id', '#'+$review.attr('id'));
							} else {
								btn.button('reset');
								jQuery('#review-message').html(json.message);
								jQuery('#review-message-container').addClass('alert-danger').show();
								jQuery('#review-message-container').removeClass('alert-success').show();
							}
						}
					} catch (e) {
						giverhubError({e : e});
					}
				},
				complete : function () {

				}
			});

			return false;
		});

		var $leave_review_textarea = jQuery('#leave-review-textarea');
		$leave_review_textarea.summernote({
			toolbar: [
				['style', ['bold', 'italic', 'underline']],
				['para', ['ul', 'ol', 'paragraph']]
			]
		});

		$leave_review_textarea.code($leave_review_textarea.data('initial-value'));

		$review_form.find('.note-editable').attr('data-placeholder', $leave_review_textarea.attr('placeholder'));

		$review_form.on('click', '.note-editable', function() {
			try {
				requireSignedIn2("You need to be signed in to leave a review.");
			} catch(e) {
				giverhubError({e:e});
			}
		});
		$review_form.on('click', '.btn-edit-nonprofit-review', function() {
			try {
				var $this = jQuery(this);

				$leave_review_textarea.removeAttr('disabled');
				$leave_review_textarea.code($this.data('current-review'));

				var $parent = $this.parent();
				var rating = parseInt($this.data('current-rating'));

				$parent.find('.note-editable').attr('contenteditable', 'true');
				$parent.find('.icon-star').each(function(i,star) {
					if (i+1 <= rating) {
						jQuery(star).addClass('voted');
					}
				});
				$this.attr('disabled','disabled');
				jQuery('#review-form-submit').removeAttr('disabled');
			} catch(e) {
				giverhubError({e:e});
			}
		});

		$review_form.on('click', '.btn-remove-nonprofit-review', function() {
			var $this = jQuery(this);
			try {

				if (!confirm('Are you sure?')) {
					return;
				}
				$this.button('loading');

				jQuery.ajax({
					url : '/charity/remove_review',
					dataType : 'json',
					type : 'post',
					data : { charity_id : $this.data('charity-id') },
					error : function() {
						giverhubError({e:e});
					},
					complete : function() {
						$this.button('reset');
					},
					success : function(json) {
						try {
							if (typeof(json) !== "object" || typeof(json.success) !== "boolean" || !json.success) {
								giverhubError({msg : 'Something went wrong..'});
							} else {
								var $existing = jQuery($this.data('review-info-id'));
								$existing.remove();
								$this.addClass('hide');
								$review_form.find('.btn-edit-nonprofit-review').addClass('hide');
								jQuery('#review-form-submit').removeClass('hide').removeAttr('disabled');
								$leave_review_textarea.code('')
								$review_form.find('.note-editable').attr('contenteditable', 'true');
								$review_form.find('.icon-star').removeClass('voted');
								giverhubSuccess({subject : 'Remove review', msg : 'Review was removed!'});
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

		jQuery('#email-charity-form').submit(function (e) {
			e.preventDefault();
			jQuery('#email-charity-send').trigger('click');
		});

		jQuery('#email-charity-send').click(function () {
			try {


				jQuery('#email-charity-message').html('');
				jQuery('#email-charity-message-container').removeClass('alert-success').removeClass('alert-danger').hide();


				var emails_val = jQuery('#email-addresses').val();

				if (!emails_val) {
					jQuery('#email-charity-message').html('Please enter atleast one Email Address');
					jQuery('#email-charity-message-container').removeClass('alert-success').addClass('alert-danger').show();
					return;

				} else {
					var emails = emails_val.replace(/\s+/, '').split(',');
					var is_valid = true;

					for (var index in emails) {
						if (emails[index])
							if (!/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,6}$/.test(emails[index])) {
								is_valid = false;
								break;
							}
					}


					if (!is_valid) {
						jQuery('#email-charity-message').html('One or more email addresses are invalid !');
						jQuery('#email-charity-message-container').removeClass('alert-success').addClass('alert-danger').show();
						return;
					}
				}

				var btn = jQuery(this);
				btn.button('loading');

				jQuery.ajax({
					url : '/charity/send_invitation',
					type : 'post',
					data : {
						charity_id : jQuery('#feature-charity-modal').data('charity-id'),
						email : jQuery('#email-addresses').val()
					},
					dataType : 'json',
					error : function () {
						giverhubError({msg : 'Request failed.'});
					},
					success : function (json) {
						try {
							if (json === undefined || !json || json.success === undefined || !json.success) {
								giverhubError({msg : 'Bad response from server.'});
							} else {
								giverhubSuccess({subject : 'Thank you!', msg : 'Thank you for helping spread the word!'});
							}
						} catch(e) {
							giverhubError({e:e});
						}
					},
					complete : function () {
						btn.button('reset');
					}
				});
			} catch (e) {
				giverhubError({e : e});
			}
		});

		jQuery('#upload-charity-photos-form')
			.fileupload()
			.bind(
			'fileuploaddone',
			function (a, b) {
				try {
					var d = jQuery.parseJSON(b.result);
					if (d.length) {
						jQuery('.no-photos-yet').hide();
					}
					var errors = [];
					jQuery.each(d, function (i, f) {
						switch (f.error) {
							case undefined:
								jQuery('.photo_grid').append('<a data-large-src="' + f.url + '" class="show-photo-in-modal" href="#"><img src="' + f.thumbnail_url + '" alt="" class="img-rounded"/></a>');
								break;
							case 'acceptFileTypes':
								errors.push(f.name + ': Bad File Type. We only accept images, such as jpg, png and gif.')
								break;
							default:
								errors.push(f.name + ': Unexpected Error: ' + f.error);
								break;
						}
						if (errors.length) {
							giverhubError({msg : 'There was problems with ' + errors.length + ' file' + (errors.length > 1 ? 's' : '') + '.<br/>' + errors.join('<br/>')});
						}
					});

				} catch (e) {
					giverhubError({e : e});
				}
			}
		)
			.bind(
			'fileuploadstart',
			function () {
				jQuery('.uploading-charity-photos').removeClass('hide');
			}
		)
			.bind(
			'fileuploadstop',
			function () {
				jQuery('.uploading-charity-photos').addClass('hide');
			}
		)
			.bind(
			'fileuploadfail',
			function (a, b) {
				giverhubError({msg : 'Something went wrong when uploading the file."' + b.errorThrown + '". Notice that files bigger than 10Mb should not be uploaded. Thank you for your patience.'});
			}
		)
		;

		jQuery(document).on('click', '.show-photo-in-modal', function () {
			try {
				var displayPhotoModal = jQuery('#display-photo-modal');
				var displayPhotoModalImg = jQuery('#display-photo-modal-img');

				displayPhotoModalImg.attr('src', jQuery(this).data('large-src'));

				displayPhotoModal.modal('show');
			} catch (e) {
				giverhubError({e : e});
			}
			return false;
		});

		jQuery(document).on('click', '.btn-submit-charity-update', function () {
			var signedIn = requireSignedIn();
			if (!signedIn) {
				return false;
			}

			var btn = jQuery(this);
			var $textArea = jQuery('#charity-update-textarea');
			try {
				btn.button('loading');

				var charityId = btn.data('charity-id');
				var updateText = $textArea.val();
				if (updateText.length < 2) {
					giverhubError({subject : 'Enter Text First', msg : 'You need to type some text into the text area above.'});
					return false;
				}
				$textArea.attr('disabled', 'disabled');

				jQuery.ajax({
					url : '/charity/submit_update',
					dataType : 'json',
					type : 'post',
					data : {
						charityId : charityId,
						updateText : updateText
					},
					error : function () {
						giverhubError({msg : 'Request Failed.'});
					},
					success : function (json) {
						try {
							if (json === undefined || !json || json.success === undefined || !json.success || json.updates === undefined) {
								giverhubError({msg : 'Bad response from server.'});
							} else {
								jQuery('#charity-updates-table').replaceWith(json.updates);
								giverhubSuccess({msg : 'Thank you for leaving the update!'});
								$textArea.val('');
							}
						} catch (e) {
							giverhubError({e : e});
						}
					},
					complete : function () {
						btn.button('reset');
						$textArea.removeAttr('disabled');
					}
				});
			} catch (e) {
				btn.button('reset');
				$textArea.removeAttr('disabled');
				giverhubError({e : e});
			}
			return false;
		});

		jQuery(document).on('focus', '#charity-update-textarea, #leave-review-textarea', function () {
			requireSignedIn();
			return false;
		});

		jQuery(document).on('click', '.btn-goto-leave-a-review', function () {
			jQuery('#leave-review-textarea').focus();
		});

		var featureCharityModal = jQuery('#feature-charity-modal');
		var featureCharityModalTextArea = jQuery('#featured-text-textarea');
		var featureCharityModalCheckBox= jQuery('#feature-this-charity-checkbox');

		function resetFeatureCharityModal() {
			featureCharityModalCheckBox.prop('checked', featureCharityModal.data('is-featured') ? true : false);
			featureCharityModalTextArea.val(featureCharityModal.data('featured-text'));
		}

		jQuery(document).on('click', '.btn-feature-charity', function() {
			resetFeatureCharityModal();
			featureCharityModal.modal('show');
			return false;
		});

		jQuery(document).on('click', '.btn-submit-feature-charity-modal', function() {
			var btn = jQuery(this);
			btn.button('loading');

			var featuredText = featureCharityModalTextArea.val();
			var isFeatured = featureCharityModalCheckBox.prop('checked');

			var data = {
				featuredText : featuredText,
				isFeatured : isFeatured ? 1 : 0,
				charityId : featureCharityModal.data('charity-id')
			};

			jQuery.ajax({
				url : '/charity/feature',
				type : 'post',
				data : data,
				dataType : 'json',
				error : function() {
					giverhubError({msg : 'Request failed.'});
				},
				success : function(json) {
					if (!json || !json.success) {
						giverhubError({msg : 'Bad response.'});
					} else {
						featureCharityModal.data('is-featured', isFeatured);
						featureCharityModal.data('featured-text', featuredText);
						featureCharityModal.modal('hide');
					}
				},
				complete : function() {
					btn.button('reset');
				}
			});
		});

		jQuery(document).on('click', '.btn-submit-mission', function() {
			var $this = jQuery(this);
			try {
				var field = $this.data('field');

				var $textarea = field == 'mission-summary' ? jQuery('#'+field+'-input') : jQuery('#'+field+'-textarea');
				var txt = $textarea.val().trim();
				var charity_id = $this.data('charity-id');

				if (field == 'mission-summary' && txt.length > 140) {
					giverhubError({msg : 'Tagline is too long (max 140 characters). You typed ' + text.length + ' characters.'});
					return false;
				}
				$this.button('loading');

				jQuery.ajax({
					url : '/charity/set_mission_summary',
					type : 'post',
					dataType : 'json',
					data : {
						field : field,
						text : txt,
						charity_id : charity_id
					},
					error : function() {
						giverhubError({msg : 'Request failed!'});
					},
					complete : function() {
						$this.button('reset');
					},
					success : function(json) {
						try {
							if (json === undefined || !json || json.success === undefined || !json.success) {
								giverhubError({msg : 'Bad response.'});
							} else {
								if (txt.length) {
									jQuery('#'+field+'-div').addClass('hide');

									if (field == 'mission') {
										var div = document.createElement('div');
										var text = document.createTextNode(txt);
										div.appendChild(text);
										txt = div.innerHTML.replace(/\n/g, '<br/>');
										jQuery('#'+field+'-p').html(txt);
									} else {
										jQuery('#'+field+'-p').text(txt);
									}

									jQuery('#'+field+'-submitted-by-container').html(jQuery('#signed_in_user_submitted_by').html());

									jQuery('#'+field+'-show-div').removeClass('hide');
								} else {
									giverhubSuccess({ msg : 'Saved.'});
								}
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
			return false;
		});

		jQuery(document).on('click', '.btn-edit-mission', function() {
			try {
				var $this = jQuery(this);
				var field = $this.data('field');

				jQuery('#'+field+'-show-div').addClass('hide');
				jQuery('#'+field+'-div').removeClass('hide');
			} catch(e) {
				giverhubError({e:e});
			}
			return false;
		});

		jQuery(document).on('click', '#submit-request-charity-admin-form', function() {
			var $this = jQuery(this);
			try {
				var $form = jQuery('#request-charity-admin-form');
				var $name = $form.find('.name');
				var $email = $form.find('.email');
				var $message = $form.find('.message');

				if ($name.length && $name.val().length < 5) {
					giverhubError({subject : 'Name missing', msg : 'The entered name is too short.'});
					return false;
				}

				if ($email.length && $email.val().length < 5) {
					giverhubError({subject : 'Email missing', msg : 'The entered email is too short.'});
					return false;
				}

				if ($message.length && $message.val().length < 5) {
					giverhubError({subject : 'Message missing', msg : 'The entered message is too short.'});
					return false;
				}

				var $images_container = jQuery('#request-charity-admin-images-container');

				var $images = $images_container.find('.image_container');
				if (!$images.length) {
					giverhubError({subject: 'Missing photo', msg : 'You need to upload atleast one photo.'});
					return false;
				}

				$this.button('loading');

				jQuery.ajax({
					url : '/charity/request_admin',
					type : 'post',
					dataType : 'json',
					data : $form.serialize() + '&temp_id='+jQuery('#upload-request-charity-admin-image-form-temp-id').val(),
					error : function() {
						giverhubError({msg : 'Request Failed.'});
					},
					success : function(json) {
						try {
							if (typeof json === "undefined" || !json || typeof json.success === "undefined" || !json.success) {
								giverhubError({msg : 'Bad response from server.'});
							} else {
								$form[0].reset();
								jQuery('#request-charity-admin-container').addClass('requested');
								jQuery('#request-charity-admin-images-container').addClass('hide').html('');
								jQuery('#upload-request-charity-admin-image-loading').addClass('hide');
								jQuery('#upload-request-charity-admin-image-container').addClass('hide');
								jQuery('#request-charity-admin-modal').modal('hide');
								giverhubSuccess({msg : 'We have received your request, we will contact you asap. Thank you.'});
							}
						} catch(e) {
							giverhubError({e:e});
						}
					},
					complete : function() {
						$this.button('reset');
					}
				});
			} catch(e) {
				$this.button('reset');
				giverhubError({e:e});
			}
			return false;
		});

		jQuery('#upload-request-charity-admin-image-form')
			.fileupload()
			.bind(
				'fileuploaddone',
				function(a,b) {
					try {
						var d = jQuery.parseJSON(b.result);

						switch(d[0].error) {
							case undefined:
								var $image_container = jQuery('<div class="image_container"></div>');
								$image_container.append(jQuery('<a data-large-src="'+d[0].url+'" class="show-photo-in-modal" href="#"><img src="'+d[0].thumbnail_url+'"></a>'));
								$image_container.append(jQuery('<button type="button" data-loading-text="X" data-temp-id="'+d[0].temp_id+'" data-image-id="'+d[0].image_id+'" class="btn btn-xs btn-danger delete_image_button">X</button>'));
								jQuery('#request-charity-admin-images-container').removeClass('hide').append($image_container);
								break;
							case 'acceptFileTypes':
								giverhubError({msg : 'Bad File Type. We only accept images, such as jpg, png and gif.'});
								break;
							default:
								giverhubError({msg : 'Unexpected Error: ' + d[0].error});
								break;
						}

					} catch(e) {
						giverhubError({e:e});
					}
				}
			)
			.bind(
				'fileuploadstart',
				function() {
					jQuery('#request-charity-admin-images-container').addClass('hide');
					jQuery('#upload-request-charity-admin-image-loading').removeClass('hide');
					jQuery('#upload-request-charity-admin-image-container').removeClass('hide');
				}
			)
			.bind(
				'fileuploadstop',
				function() {
					jQuery('#upload-request-charity-admin-image-loading').addClass('hide');
				}
			)
			.bind(
				'fileuploadfail',
				function(a,b) {
					giverhubError({msg : 'Something went wrong when uploading the file."' + b.errorThrown + '". Notice that files bigger than 10Mb should not be uploaded. Thank you for your patience.'});
					jQuery('#request-charity-admin-images-container').addClass('hide').html('');
					jQuery('#upload-request-charity-admin-image-loading').addClass('hide');
					jQuery('#upload-request-charity-admin-image-container').addClass('hide');
				}
			);

		jQuery('#request-charity-admin-images-container').on('click', '.delete_image_button', function() {
			var $this = jQuery(this);
			try {
				var image_id = $this.data('image-id');
				var temp_id = $this.data('temp-id');
				$this.button('loading');
				jQuery.ajax({
					url : '/upload/delete_request_charity_admin_image',
					type : 'post',
					dataType : 'json',
					data : {
						image_id : image_id,
						temp_id : temp_id
					},
					error : function() {
						giverhubError({msg : 'Request failed!'});
						$this.button('reset');
					},
					success : function(json) {
						try {
							if (json === undefined || !json || json.success === undefined || !json.success) {
								giverhubError({msg : 'Bad Response!'});
							} else {
								$this.parent().remove();
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

			return false;
		});


	} catch (e) {
		giverhubError({e : e});
	}



});
