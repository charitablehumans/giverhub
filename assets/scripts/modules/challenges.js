try {
	jQuery(document).ready(function() {
		try {
			var $create_challenge_form = jQuery('.create-challenge-form');
			var $challenge_preview_container = jQuery('.challenge-preview-container');

			if ($create_challenge_form.length) {

				var from_user_img_url = $create_challenge_form.data('from-user-img-url');
				var from_user_name = $create_challenge_form.data('from-user-name');
				var from_user_first_name = $create_challenge_form.data('from-user-first-name');

				var challenge_desc_placeholder = 'I CHALLENGE you to: ';
				var challenge_desc_regex = new RegExp('^'+challenge_desc_placeholder);

				function fix_position() {
					var start = this.selectionStart;
					var end = this.selectionEnd;

					var $this = jQuery(this);

					var placeholder = $this.data('placeholder');
					var placeholder_length = placeholder.length;

					if (start < placeholder_length || end < placeholder_length) {
						this.selectionStart = placeholder_length;
						this.selectionEnd = placeholder_length;
					}
				}
				function escapeHtml(text) {
					return text
						.replace(/&/g, "&amp;")
						.replace(/</g, "&lt;")
						.replace(/>/g, "&gt;")
						.replace(/"/g, "&quot;")
						.replace(/'/g, "&#039;");
				}

				function nl2br (str, is_xhtml) {
					var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
					return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
				}

				function renderPreview() {
					if ($challenge_preview_container.length) {
						var challenge = get_challenge({validate : false});
						if (GIVERHUB_DEBUG) {
							console.dir(challenge);
						}

						challenge.description = nl2br(escapeHtml(challenge.description));
						$challenge_preview_container.html(Handlebars.templates.challenge_preview_block(challenge));
						$challenge_preview_container.find('.gh_popover').popover();
					}
				}

				$create_challenge_form.on('keyup', '.challenge_description', fix_position);
				$create_challenge_form.on('change', '.challenge_description', fix_position);
				$create_challenge_form.on('click', '.challenge_description', fix_position);

				$create_challenge_form.on('keyup', '.challenge_description', function() {
					try {
						var $this = jQuery(this);
						var start = this.selectionStart;
						var end = this.selectionEnd;

						if (!challenge_desc_regex.test($this.val())) {
							if ($this.data('prev-value')) {
								$this.val(challenge_desc_placeholder + $this.data('prev-value'));
							} else {
								$this.val(challenge_desc_placeholder);
							}

							if (start < challenge_desc_placeholder.length || end < challenge_desc_placeholder.length) {
								this.selectionStart = challenge_desc_placeholder.length;
								this.selectionEnd = challenge_desc_placeholder.length;
							} else {
								this.selectionStart = start;
								this.selectionEnd = end;
							}
						} else {
							$this.data('prev-value', $this.val().substring(challenge_desc_placeholder.length));
						}



					} catch(e) {
						giverhubError({e:e});
					}
					return true;
				});

				function create_challenge(challenge) {
					$create_challenge_form.$friend_chosen = $create_challenge_form.find('.challenge_friend_chosen');
					$create_challenge_form.$friend_input = $create_challenge_form.find('.challenge_friend');
					$create_challenge_form.$friend_result = $create_challenge_form.find('.challenge_friend_results');

					$create_challenge_form.$charity_chosen = $create_challenge_form.find('.challenge_charity_chosen');
					$create_challenge_form.$charity_input = $create_challenge_form.find('.challenge_charity');
					$create_challenge_form.$charity_result = $create_challenge_form.find('.challenge_charity_results');

					var $desc = $create_challenge_form.find('.challenge_description');
					$desc.data('placeholder', challenge_desc_placeholder);
					if (!$desc.val().length) {
						$desc.val(challenge_desc_placeholder);
					}

					if ($create_challenge_form.data('challenge-id')) {
						renderPreview();
					}
				}
				create_challenge({});

				function get_challenge(options) {
					var validate = options.validate;
					var $form = $create_challenge_form;

					var challenge = {
						from_user_name : from_user_name,
						from_user_img_url : from_user_img_url,
						from_user_first_name : from_user_first_name
					};

					var $name = $form.find('.challenge_name');
					var name_val = $name.val().trim();
					if (validate && !name_val.length) {
						giverhubError.hideEvent = function() {
							$name.focus();
						};
						giverhubError({subject : 'Incomplete challenge.', msg : 'Name is missing.'});
						return false;
					}
					challenge.name = name_val;
					challenge.name_with_challenge = name_val;
					if (name_val.match(/challenge/i) === null) {
						challenge.name_with_challenge = name_val + '-CHALLENGE';
					}

					var $description = $form.find('.challenge_description');
					var description = $description.val().trim();
					var description_placeholder = $description.data('placeholder');
					if (validate && description.length < description_placeholder.length + 5) {
						giverhubError({subject : 'Incomplete challenge.', msg : 'Your description must contain at least 5 characters.'});
						giverhubError.hideEvent = function() {
							$description.focus();
							var tmpStr = $description.val();
							$description.val('');
							$description.val(tmpStr);
						};
						return false;
					}
					challenge.description = description.substring(description_placeholder.length).trim();


					var $lis = $create_challenge_form.find('.emails').find('li');
					if (validate && !$lis.length) {
						giverhubError({subject : 'Incomplete challenge.', msg : 'You need to enter atleast 1 email.'});
						giverhubError.hideEvent = function() {
							$create_challenge_form.$friend_input.focus();
						};
						return false;
					}
					var emails = [];
					$lis.each(function(i,li) {
						var $li = jQuery(li);
						emails.push($li.data('email'));
					});
					challenge.emails = emails;

					var $chosen_charity = $create_challenge_form.$charity_chosen.find('li');
					if (validate && $chosen_charity.length != 1) {
						giverhubError({subject : 'Incomplete challenge.', msg : 'You need to pick a nonprofit.'});
						giverhubError.hideEvent = function() {
							$create_challenge_form.$charity_input.focus();
						};
						return false;
					}
					challenge.charity_id = $chosen_charity.data('charity-id');
					challenge.charity_name = $chosen_charity.find('a').attr('title');
					challenge.charity_score = $chosen_charity.data('charity-score');
					challenge.charity_tagline = $chosen_charity.data('charity-tagline');


					var $youtube_container = $create_challenge_form.find('.youtube-preview-container');
					var video_id = $youtube_container.data('youtube-video-id');
					if (validate && (typeof(video_id) !== "string" || !video_id.length)) {
						giverhubError({subject : 'Incomplete challenge.', msg : 'You need to enter a youtube video URL.'});
						giverhubError.hideEvent = function() {
							$create_challenge_form.find('.challenge_video').focus();
						};
						return false;
					}
					challenge.video_id = video_id;


					var $challenge_dedication_input = $create_challenge_form.find('.challenge_dedication');
					if (validate && !$challenge_dedication_input.hasClass('hide')) { // only take dedication if the input is not hidden.. if it is hidden we simply ignore it.
						var dedication = $challenge_dedication_input.val().trim();
						if (dedication.length < 3) {
							giverhubError({subject : 'Incomplete challenge.', msg : 'You need to enter a dedication message of atleast 3 characters.'});
							giverhubError.hideEvent = function() {
								$challenge_dedication_input.focus();
							};
							return false;
						} else {
							challenge.dedication = dedication;
						}
					}

					if ($create_challenge_form.data('challenge-id')) {
						challenge.challenge_id = $create_challenge_form.data('challenge-id');
					}

					return challenge;
				}

				function save_challenge(params) {
					var challenge = get_challenge({validate: true});

					if (challenge === false) {
						return false;
					}

					if (typeof(params.publish) === "boolean" && params.publish) {
						challenge.publish = '1';
					} else {
						challenge.publish = '0';
					}

					try {
						params.btn.button('loading');

						var $my_challenges_table = jQuery('.my-challenges-table');
						if ($my_challenges_table.length) {
							challenge.my_challenges_table = true;
						}

						jQuery.ajax({
							url : '/challenge/save',
							type : 'post',
							dataType : 'json',
							data : challenge,
							error : function() {
								giverhubError({msg : 'Request Failed'});
							},
							complete : function() {
								params.btn.button('reset');
							},
							success : function(json) {
								try {
									if (typeof(json) === "undefined" || typeof(json.success) === "undefined" || !json.success || typeof(json.challenge_id) === "undefined") {
										giverhubError({msg : 'Bad response from server!'});
									} else {
										$create_challenge_form.data('challenge-id', json.challenge_id);
										if (typeof(json.my_challenges_table) === "string") {
											$my_challenges_table.replaceWith(json.my_challenges_table);
										}
										params.success(challenge, json.challenge_id);
									}
								} catch(e) {
									giverhubError({e:e});
								}
							}
						});
					} catch(e) {
						params.btn.button('reset');
						giverhubError({e:e});
					}
					return true;
				}

				$create_challenge_form.on('click', '.btn-challenge-save-draft', function() {
					try {
						save_challenge({
							btn : jQuery(this),
							success : function() {
								giverhubSuccess({subject : 'Saved', msg : 'The challenge has been saved.'});
							}
						});
					} catch(e) {
						giverhubError({e:e});
					}
					return false;
				});

				$create_challenge_form.on('click', '.btn-challenge-publish', function() {
					try {
						save_challenge({
							btn : jQuery(this),
							success : function(challenge, challenge_id) {
								giverhubSuccess({subject : 'Great!', msg : 'Please stand by while being taken to the challenge page.'});
								setTimeout(function() {window.location = '/challenge/'+challenge_id;}, 1000);
							},
							publish : true
						});
					} catch(e) {
						giverhubError({e:e});
					}
					return false;
				});


				$create_challenge_form.on('click', '.challenge_dedication_checkbox', function() {
					try {
						var $challenge_dedication_input = $create_challenge_form.find('.challenge_dedication');
						$challenge_dedication_input.toggleClass('hide');

						renderPreview();
					} catch(e) {
						giverhubError({e:e});
					}
				});

				var $emails = $create_challenge_form.find('.emails');
				$emails.on('click', '.btn-danger', function() {
					try {
						var $this = jQuery(this);
						$this.closest('li').remove();
						renderPreview();
					} catch(e) {
						giverhubError({e:e});
					}
				});

				var emailRegex = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

				$create_challenge_form.on('keyup', '.challenge_friend', function(e) {
					var code = e.which; // recommended to use e.which, it's normalized across browsers
					if(code==13)e.preventDefault();
					if(code==32||code==13||code==188||code==186){
						$create_challenge_form.find('.btn-challenge-add-email').trigger('click');
					}
				});

				$create_challenge_form.on('click', '.btn-challenge-add-email', function() {
					try {
						var $lis = $emails.find('li');
						if ($lis.length >= 3) {
							giverhubError({msg : 'You can not add more than 3 emails.'});
							return false;
						}

						var $email_input = $create_challenge_form.find('.challenge_friend');
						var email = $email_input.val().trim();

						var exists = false;
						$lis.each(function(i,li) {
							if (jQuery(li).data('email') == email) {
								exists = true;
							}
						});

						if (exists) {
							giverhubError({msg : 'The email has already been added'});
							return false;
						}

						if (!emailRegex.test(email)) {
							giverhubError({msg : 'Email is invalid.'});
							return false;
						}

						$emails.append(jQuery('<li data-email="'+email+'">'+email+'<button type="button" class="btn btn-danger btn-xs">x</button></li>'));

						$email_input.val('');

						renderPreview();
					} catch(e) {
						giverhubError({e:e});
					}
				});

				var charity_results_store = {};
				var clearCharityButtonString = '<button type="button" class="btn btn-xs btn-danger btn-clear-charity">x</button>';

				$create_challenge_form.on('keyup', '.challenge_charity', function() {
					try {
						var $this = jQuery(this);
						var value = $this.val();

						var $result = $create_challenge_form.$charity_result;

						if (!value.length) {
							$result.hide();
							return true;
						}
						$result.show();

						var res = charity_results_store[value];
						if (res === undefined) {
							$result.html('<img src="/images/ajax-loaders/ajax-loader.gif">');
							charity_results_store[value] = {loading : true};
							jQuery.ajax({
								url : '/members/bet_friends_charity',
								type : 'get',
								dataType : 'json',
								data : { search : value },
								error : function() {
									giverhubError({msg : 'Request Failed.'});
								},
								success : function(json) {
									try {
										if (json === undefined || !json || json.success === undefined || !json.success || json.search === undefined || json.results === undefined) {
											giverhubError({msg : 'Bad response!'});
										} else {
											if (charity_results_store[json.search] === undefined) {
												return;
											}
											if (!charity_results_store[json.search]['loading']) {
												return;
											}
											charity_results_store[json.search]['loading'] = false;
											charity_results_store[json.search]['results'] = json.results;
											if ($this.val() == json.search) {
												$result.html(json.results);
											}
										}
									} catch(e) {
										giverhubError({e:e});
									}
								},
								complete : function() {}
							});
						} else {
							if (res['loading']) {
								$result.html('<img src="/images/ajax-loaders/ajax-loader.gif">');
							} else {
								$result.html(res['results']);
							}
						}
					} catch(e) {
						giverhubError({e:e});
					}
					return true;
				});

				$create_challenge_form.on('click', '.challenge_charity_chosen .select-charity', function() {
					return false;
				});

				$create_challenge_form.on('click', '.challenge_charity_results .select-charity', function() {
					try {
						var $this = jQuery(this);
						var $li = $this.parent();
						var $newLi = $li.clone();

						var $input = $create_challenge_form.$charity_input;
						var $chosen = $create_challenge_form.$charity_chosen;
						var $result = $create_challenge_form.$charity_result;

						$newLi.find('a').append(clearCharityButtonString);
						$input.hide();
						$chosen.html($newLi);
						$chosen.show();
						$result.hide();

						renderPreview();
					} catch(e) {
						giverhubError({e:e});
					}
					return false;
				});

				$create_challenge_form.on('click', '.btn-clear-charity', function() {
					try {
						var $input = $create_challenge_form.$charity_input;
						var $chosen = $create_challenge_form.$charity_chosen;

						$chosen.hide();
						$chosen.html('');
						$input.val('').show().focus();

						renderPreview();
					} catch(e) {
						giverhubError({e:e});
					}
					return false;
				});

				$create_challenge_form.on('keyup', '.challenge_video', function() {
					try {
						var $this = jQuery(this);
						var val = $this.val().trim();

						var found_video = false;
						if (val.match(/youtube\.com/i) !== null) {
							var uri = window.parseUri(val);
							if (uri && uri.queryKey && uri.queryKey.v) {
								var video_id = uri.queryKey.v;
								$create_challenge_form.find('.youtube-preview-container').replaceWith(
									'<div ' +
										'class="youtube-preview-container" ' +
										'data-youtube-video-id="'+video_id+'">' +
										'<iframe ' +
											'class="youtube-player youtube-preview-iframe" ' +
											'type="text/html" ' +
											'width="100%" ' +
											'height="" ' +
											'src="https://www.youtube.com/embed/'+video_id+'" ' +
											'allowfullscreen ' +
											'frameborder="0">' +
										'</iframe>' +
									'</div>'
								);
								found_video = true;
							}
						}

						if (!found_video) {
							$create_challenge_form.find('.youtube-preview-container').html('').data('youtube-video-id', null);
						}

						renderPreview();
					} catch(e) {
						giverhubError({e:e});
					}
					return true;
				});


				if ($challenge_preview_container.length) {
					$create_challenge_form.on('input', '.trigger-review', function () {
						try {
							renderPreview();
						} catch (e) {
							giverhubError({e : e});
						}
					});
				}
			}

			if (jQuery('.challenge-info-block').length || jQuery('.my-challenges-table').length) {
				function accept_reject($this, accept) {
					if (!body.data('signed-in')) {
						jQuery('#signin-or-join-first-modal').modal('show');
						return false;
					}

					var $parent = $this.parent();


					$this.button('loading');
					var challenge_id = $parent.data('challenge-id');

					jQuery.ajax({
						url : '/challenge/accept_reject',
						dataType : 'json',
						type : 'post',
						data : { accept : (accept ? 1 : 0), challenge_id : challenge_id },
						error : function() {
							giverhubError({msg : 'Request Failed'});
							$this.button('reset');
						},
						success : function(json) {
							try {
								$this.button('reset');
								if (typeof(json) !== 'object' || typeof(json.success) !== 'boolean' || !json.success || typeof(json.challenge_info_html)!=="string") {
									giverhubError({msg : 'Bad response from server.'});
								} else {
									$this.closest('.challenge-info-block').replaceWith(json.challenge_info_html);
								}
							} catch(e) {
								giverhubError({e:e});
							}
						}
					});
					return true;
				}

				jQuery(document).on('click', '.btn-accept-challenge', function() {
					try {
						accept_reject(jQuery(this), true);
					} catch(e) {
						giverhubError({e:e});
					}
					return false;
				});

				jQuery(document).on('click', '.btn-reject-challenge', function() {
					try {
						accept_reject(jQuery(this), false);
					} catch(e) {
						giverhubError({e:e});
					}
					return false;
				});

				/*jQuery(document).on('click', '.btn-clone-challenge', function() {
					var $this = jQuery(this);
					try {
						$this.button('loading');

						var challenge = $this.data('challenge-json');

						challenge.id = null;

						create_challenge(challenge);

					} catch(e) {
						giverhubError({e:e});
					}
					$this.button('reset');
					return false;
				});*/

				jQuery(document).on('click', '.btn-resend-email-challenge', function() {
					var $this = jQuery(this);
					try {
						$this.button('loading');
						jQuery.ajax({
							url : '/challenge/resend',
							type : 'post',
							data : {
								challenge_user_id : $this.data('challenge-user-id')
							},
							dataType : 'json',
							error : function() {
								giverhubError({msg : 'Request Failed'});
							},
							complete : function() {
								$this.button('reset');
							},
							success : function(json) {
								try {
									checkSuccess(json);
									if (typeof(json.msg) === "string") {
										giverhubError({
											msg : json.msg
										});
									} else {
										giverhubSuccess({
											subject : "Success!",
											msg : "An email has been sent to the user again."
										});
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

				jQuery(document).on('click', '.btn-upload-challenge-video', function(event) {
					var $this = jQuery(this);
					try {
						var $form = $this.closest('form');
						var $file_input = $form.find('.challenge-upload-video-input');
						var $loader = $form.find('.upload-challenge-loader');

						$this.button('loading');
						$loader.removeClass('hide');
						jQuery.ajax({
							url: '/challenge/upload_video',
							type: 'post',
							dataType : 'json',
							data: new FormData($form[0]),
							processData: false,
							contentType: false,
							error : function(a,b,c) {
								var msg = 'Upload Failed.';
								if (typeof(c) === 'string') {
									if (c == 'Request Entity Too Large') {
										giverhubError({subject : 'Video too large!', msg : 'Your video is too large. Maximum size is 100MB.'});
										return;
									} else {
										msg+= ' (' + c + ')';
									}
								}
								giverhubError({msg : msg});
							},
							complete : function() {
								$this.button('reset');
								$loader.addClass('hide');
							},
							success : function(json) {
								try {
									if (typeof(json) !== "object" || typeof(json.success) !== "boolean" || !json.success || typeof(json.challenge_info_html) !== "string") {
										if (typeof(json.msg) === "string") {
											giverhubError({subject : 'Upload file', msg : json.msg});
										} else {
											giverhubError({msg : 'Bad response from server.'});
										}
									} else {
										var $newHtml = jQuery(json.challenge_info_html);
										$this.closest('.challenge-info-block').replaceWith($newHtml);
										var $your_video = $newHtml.find('.your-video');
										jQuery('html,body').animate({scrollTop: $your_video.offset().top-80}, 1000);
									}
								} catch(e) {
									giverhubError({e:e});
								}
							}
						});
						event.preventDefault();
					} catch(e) {
						giverhubError({e:e});
					}
				});

				var allowed_file_extensions = ['3gp', 'avi', 'mpg', 'mpeg', 'mp4', 'webm', 'ogv', 'flv'];
				var allowed_file_regex = new RegExp('\.('+allowed_file_extensions.join('|')+')$', 'i');

				jQuery(document).on('change', '.challenge-upload-video-input', function() {
					try {
						var val = jQuery(this).val();
						var $form = jQuery(this).closest('form');
						var $submit_button = $form.find('.btn-upload-challenge-video');
						var $filename_div = $form.find('.selected-challenge-video-filename');

						function go_ahead() {
							$submit_button.removeAttr('disabled');
							$filename_div.html(val.split(/(\\|\/)/g).pop());
						}

						if (val) {
							if (val.match(allowed_file_regex) === null) {
								giverhubError({subject : 'Invalid video', msg : 'Video needs to be one of the following types: ' + allowed_file_extensions.join(', ')});
								$submit_button.attr('disabled', 'disabled');
								$filename_div.html('');
							} else {

								var input = this;
								if (input.files && input.files[0] && input.files[0].size) {
									var mb = Math.round((input.files[0].size / 1024 / 1024)*10) / 10;
									if (mb > 100) {
										giverhubError({subject : 'Video too large!', msg : 'Your video is too large. ('+mb+'MB). Maximum size is 100MB.'});
										$submit_button.attr('disabled', 'disabled');
										$filename_div.html('');
									} else {
										go_ahead(); // file size is ok
									}
								} else {
									go_ahead(); // could not determine file size,, so let the user try..
								}
							}
						} else {
							$submit_button.attr('disabled', 'disabled');
							$filename_div.html('');
						}
					} catch(e) {
						giverhubError({e:e});
					}
					return true;
				});

				videojs.options.flash.swf = body.data('base-url') + 'assets/scripts/video-js/video-js.swf';

				jQuery(document).on('keyup', '.youtube-url-challenge-input', function() {
					try {
						var $this = jQuery(this);
						var val = $this.val().trim();

						var $form = $this.closest('form');

						var $submit_button = $form.find('.btn-submit-youtube-url-challenge');
						var $preview_container = $form.find('.youtube-preview-container');

						var found_video = false;
						if (val.match(/youtube\.com/i) !== null) {
							var uri = window.parseUri(val);
							if (uri && uri.queryKey && uri.queryKey.v) {
								var video_id = uri.queryKey.v;
								$preview_container.replaceWith(
									'<div ' +
										'class="youtube-preview-container" ' +
										'data-youtube-video-id="'+video_id+'">' +
										'<iframe ' +
										'class="youtube-player youtube-preview-iframe" ' +
										'type="text/html" ' +
										'width="100%" ' +
										'height="" ' +
										'src="https://www.youtube.com/embed/'+video_id+'" ' +
										'allowfullscreen ' +
										'frameborder="0">' +
										'</iframe>' +
										'</div>'
								);
								found_video = true;
								$submit_button.removeAttr('disabled');
							}
						}

						if (!found_video) {
							$preview_container.html('').data('youtube-video-id', null);
							$submit_button.attr('disabled','disabled');
						}
					} catch(e) {
						giverhubError({e:e});
					}
					return true;
				});

				jQuery(document).on('click', '.btn-submit-youtube-url-challenge', function() {
					var $this = jQuery(this);
					try {
						var $form = $this.closest('form');
						var $preview_container = $form.find('.youtube-preview-container');
						var video_id = $preview_container.data('youtube-video-id');
						var challenge_id = $this.data('challenge-id');
						$this.button('loading');
						jQuery.ajax({
							url : '/challenge/upload_youtube',
							dataType : 'json',
							type : 'post',
							data : { video_id : video_id, challenge_id : challenge_id },
							error : function() {
								giverhubError({msg : 'Request Failed'});
							},
							complete : function() {
								$this.button('reset');
							},
							success : function(json) {
								try {
									if (typeof(json) !== "object" || typeof(json.success) !== "boolean" || !json.success || typeof(json.challenge_info_html) !== "string") {
										giverhubError({msg : 'Bad response'});
									} else {
										var $newHtml = jQuery(json.challenge_info_html);
										$this.closest('.challenge-info-block').replaceWith($newHtml);
										var $your_video = $newHtml.find('.your-video');
										jQuery('html,body').animate({scrollTop: $your_video.offset().top-80}, 1000);
									}
								} catch(e) {
									giverhubError({e:e});
								}
							}
						});
					} catch(e) {
						giverhubError({e:e});
					}
					return false;
				});

			} // endif jQuery('.challenge-info-block').length)

			jQuery(document).on('click', '.btn-share-challenge-on-facebook', function() {
				try {
					var $this = jQuery(this);

					FB.ui({
						method: 'feed',
						link: $this.data('challenge-url')
					});
				} catch(e) {
					giverhubError({e:e});
				}
				return false;
			});
		} catch(e) {
			giverhubError({e:e});
		}
	});
} catch(e) {
	giverhubError({e:e});
}