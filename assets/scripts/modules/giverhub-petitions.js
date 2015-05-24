try {
	jQuery(document).ready(function() {
		try {

			jQuery('#petitiondeadlinepicker').datetimepicker({
				pickTime: false,
				minDate : moment().subtract('days', 1),
				format: 'MM/DD/YY'
			});


			var $publish_success_modal = jQuery('#giverhub-petition-publish-success-modal');
			var $preview_modal = jQuery('#giverhub-petition-preview-modal');

			function publish_success_modal(petition) {
				$publish_success_modal.html(Handlebars.templates.giverhub_petition_publish_success_modal(petition));
				$publish_success_modal.data('petition_id', petition.id);
				$publish_success_modal.data('petition-url', petition.url);
				$publish_success_modal.find('.info-sign').popover();
				$publish_success_modal.modal('show');
				if (GIVERHUB_DEBUG) {
					console.dir(petition);
				}
			}

			function publish_petition(params) {
				jQuery.ajax({
					url : '/giverhub_petition/publish',
					type : 'post',
					dataType : 'json',
					data : params.petition,
					success : function(json) {
						try {
							if (!json || json === undefined || json.success === undefined || !json.success || json.petition === undefined) {
								params.error();
								giverhubError({msg : 'Bad response from server.'});
							} else {
								params.success();
								publish_success_modal(json.petition);
							}
						} catch(e) {
							params.error();
							giverhubError({e:e});
						}
					},
					error : function() {
						giverhubError({msg : 'Request Failed'});
						try {
							params.error();
						} catch(e) {
							giverhubError({e:e});
						}
					},
					complete : function() {
						try {
							params.complete();
						} catch(e) {
							giverhubError({e:e});
						}
					}
				});
			}

			function preview_petition(petition, $create_modal) {
				$preview_modal.html(Handlebars.templates.giverhub_petition_preview_modal(petition));
				$preview_modal.petition = petition;
				$preview_modal.$create_modal = $create_modal;
				$preview_modal.modal('show');
			}

			$preview_modal.on('click', '.btn-edit', function() {
				try {
					$preview_modal.modal('hide');
					$preview_modal.$create_modal.modal('show');
				} catch(e) {
					giverhubError({e:e});
				}
			});

			$preview_modal.on('click', '.btn-publish', function() {
				var $this = jQuery(this);
				try {
					$this.button('loading');

					publish_petition({
						petition: $preview_modal.petition,
						error : function() {},
						complete : function() {
							$this.button('reset');
						},
						success : function() {
							$preview_modal.modal('hide');
						}
					});

				} catch(e) {
					$this.button('reset');
					giverhubError({e:e});
				}
				return false;
			});

			function create_form_error($input, msg) {
				var $row = $input.closest('.row');
				var $alert = $row.find('.alert');
				var $message = $alert.find('.message');
				$message.html(msg);
				$row.addClass('has-error');
				$input.focus();
			}

			function validate_create_form($modal) {
				$modal.find('.has-error').removeClass('has-error');

				var has_errors = false;

				var $why = $modal.find('textarea.why');
				var why_val = $why.code().trim();
				if (!why_val.length) {
					create_form_error($why, '<strong>Oops!</strong> Why is missing!');
					has_errors = true;
				}

				var $what = $modal.find('input.what');
				var what_val = $what.val().trim();
				if (what_val.length < 8) {
					create_form_error($what, 'How is too short. minimum 8 characters!');
					has_errors = true;
				}
				if (!what_val.length) {
					create_form_error($what, '<strong>Oops!</strong> How is missing!');
					has_errors = true;
				}

				var $target = $modal.find('input.target');
				var target_val = $target.val().trim();
				if (target_val.length < 2) {
					create_form_error($target, 'Target is too short. minimum 2 characters!');
					has_errors = true;
				}
				if (!target_val.length) {
					create_form_error($target, '<strong>Oops!</strong> Target is missing!');
					has_errors = true;
				}

				if (has_errors) {
					return false;
				}

				var petition = {
					target : target_val,
					what : what_val,
					why : why_val
				};

				var $upl_img = $modal.find('.upl-img');
				if (!$upl_img.hasClass('hide')) {
					petition.photo_id = $upl_img.data('photo-id');
					petition.photo_src = $upl_img.data('photo-src');
					//petition.photo_src = $upl_img.attr('src');
				}

				var $add_url_preview = $modal.find('.add-url-preview');
				if (!$add_url_preview.hasClass('hide')) {
					if ($add_url_preview.hasClass('img')) {
						petition.img_url = $add_url_preview.data('img-url');
					} else if ($add_url_preview.hasClass('youtube-vid')) {
						petition.video_id = $add_url_preview.data('video-id');
					}
				}

				if (!petition.photo_id && !petition.img_url && !petition.video_id) {
					petition.no_media = jQuery("div.giverhub-petition-no-media-block").html();
				}				
				petition.giverhub_petition_added_by = jQuery("div.giverhub-petition-added-by-block").html();

				return petition;
			}

			function add_url_error($modal, message) {
				var $alert = $modal.find('.add-url-alert');
				var $message = $alert.find('.message');
				$message.html(message);
				$alert.removeClass('hide');
			}

			jQuery(document).on('click', '.btn-create-petition-from-block', function() {
				if (!GIVERHUB_DEBUG) {
					giverhubSuccess({subject : 'Feature not Ready', msg : 'This feature is not ready yet. Please check again in the near future.', hideOk: true});
					return false;
				}
				var $this = jQuery(this);
				try {
					$this.button('loading');

					var $modal = jQuery(Handlebars.templates.giverhub_petition_create_modal());
					$body.append($modal);
					$modal.find('textarea').summernote({
						toolbar: [
							['style', ['bold', 'italic', 'underline']],
							['para', ['ul', 'ol', 'paragraph']]
						]
					});
					var $textarea = $modal.find('textarea');
					$textarea.code('');
					$modal.find('.note-editable').attr('data-placeholder', $textarea.attr('placeholder'));

					$modal.find('.temp-id').val($this.data('temp-id'));


					var $add_url_container = $modal.find('.add-url-container');
					var $add_url_input = $add_url_container.find('.add-url-input');
					var $add_url_preview = $modal.find('.add-url-preview');
					var $add_url_alert = $modal.find('.add-url-alert');
					var $add_url_btn = $modal.find('.add-url-btn');

					var $btn_delete_preview = jQuery('<button type="button" class="btn btn-danger btn-xs btn-delete-preview-url">x</button>');


					var $upl_btn = $modal.find('.upl-btn');

					var $upload_photo_btn = $upl_btn.find('.upload-photo-btn');
					var $petition_photo_input = $upl_btn.find('.petition-photo-input');

					var $upl_loading = $modal.find('.upl-loading');
					var $upl_img = $modal.find('.upl-img');

					function fileUploadError(msg) {
						$upl_btn.removeClass('hide');
						$upl_loading.addClass('hide');
						$upl_img.addClass('hide');
						giverhubError({msg : msg });
					}

					$modal.find('form')
						.fileupload()
						.bind(
							'fileuploaddone',
							function(a,b) {
								try {
									var d = jQuery.parseJSON(b.result);
									switch(d[0].error) {
										case undefined:
											$upl_loading.addClass('hide');
											$upl_img.find('img').attr('src', d[0].thumbnail_url);
											$upl_img.data('photo-id', d[0].photo_id);
											$upl_img.data('photo-src', d[0].thumbnail_url);
											$upl_img.removeClass('hide');
											$add_url_input.attr('disabled', 'disabled');
											$add_url_btn.attr('disabled', 'disabled');
											break;
										case 'acceptFileTypes':
											fileUploadError('Bad File Type. We only accept images, such as jpg, png and gif.');
											break;
										default:
											fileUploadError('Unexpected Error: ' + d[0].error);
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
								$upl_btn.addClass('hide');
								$upl_loading.removeClass('hide');
							}
						)
						.bind(
							'fileuploadfail',
							function(a,b) {
								fileUploadError('Something went wrong when uploading the file."' + b.errorThrown + '". Notice that files bigger than 10Mb should not be uploaded. Thank you for your patience.');
							}
						)
					;

					$modal.on('click', '.btn-delete-create-giverhub-petition-image', function() {
						var $this = jQuery(this);
						try {
							$this.button('loading');

							var tempId = $modal.find('.temp-id').val();

							jQuery.ajax({
								url : '/upload/delete_petition_create_image',
								type : 'post',
								dataType : 'json',
								data : { tempId : tempId },
								error : function() {
									giverhubError({msg : 'Request Failed!'});
								},
								success : function(json) {
									try {
										if (json === undefined || !json || json.success === undefined || !json.success) {
											giverhubError({msg : 'Bad response from server!'});
										} else {
											$upl_img.addClass('hide');
											$upl_btn.removeClass('hide');
											$add_url_input.removeAttr('disabled');
											$add_url_btn.removeAttr('disabled');
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
					});

					$modal.on('click', '.btn-preview', function() {
						var $this = jQuery(this);
						try {
							$this.button('loading');
							var petition = validate_create_form($modal);
							if (petition === false) {
								$this.button('reset');
							} else {
								$modal.modal('hide');
								$this.button('reset');
								preview_petition(petition, $modal);
							}
						} catch(e) {
							$this.button('reset');
							giverhubError({e:e});
						}
						return false;
					});

					$modal.on('click', '.btn-publish', function() {
						var $this = jQuery(this);
						try {
							$this.button('loading');
							var petition = validate_create_form($modal);
							if (petition === false) {
								$this.button('reset');
							} else {
								publish_petition({
									petition: petition,
									error : function() {},
									complete : function() {
										$this.button('reset');
									},
									success : function() {
										$modal.modal('hide');
									}
								});
							}
						} catch(e) {
							$this.button('reset');
							giverhubError({e:e});
						}
						return false;
					});

					$modal.on('click', '.add-url-btn', function() {
						var $this = jQuery(this);
						try {
							$this.button('loading');
							$add_url_alert.addClass('hide');
							var url = $add_url_input.val().trim();

							if (!url.length) {
								add_url_error($modal, 'Enter an url please.');
								$this.button('reset');
								return false;
							}
							var has_video = false;
							if (url.indexOf('youtube.com') != -1) {
								uri = window.parseUri(url);
								if (uri && uri.queryKey && uri.queryKey.v) {
									$add_url_container.addClass('hide');
									var video_id = uri.queryKey.v;
									$add_url_preview.html(
										'<iframe ' +
											'class="youtube-player youtube-preview-iframe" ' +
											'type="text/html" ' +
											'width="100%" ' +
											'height="" ' +
											'src="https://www.youtube.com/embed/'+video_id+'" ' +
											'allowfullscreen ' +
											'frameborder="0">' +
										'</iframe>'
									).append($btn_delete_preview).addClass('youtube-vid').removeClass('img').data('video-id', video_id).removeClass('hide');
									has_video = true;

									$upload_photo_btn.attr('disabled', 'disabled');
									$petition_photo_input.css('display', 'none');

									$this.button('reset');
								}
							}

							if (!has_video) {
								var $img = jQuery('<img/>');
								$img.bind({
									load: function() {
										$add_url_container.addClass('hide');
										$add_url_preview.addClass('img').removeClass('youtube-vid').data('img-url', url).removeClass('hide');

										$upload_photo_btn.attr('disabled', 'disabled');
										$petition_photo_input.css('display', 'none');

										$this.button('reset');
									},
									error: function() {
										add_url_error($modal, 'The url you entered could not be loaded.');
										$this.button('reset');
									}
								});

								$add_url_preview.html($img);
								$add_url_preview.append($btn_delete_preview);
								$img.attr('src',url);
							}

						} catch(e) {
							$this.button('reset');
							giverhubError({e:e});
						}
						return false;
					});

					$modal.on('click', '.btn-delete-preview-url', function() {
						try {
							$add_url_preview.addClass('hide').removeClass('img').removeClass('youtube-vid');
							$add_url_alert.addClass('hide');
							$add_url_input.val('');
							$add_url_container.removeClass('hide');

							$upload_photo_btn.removeAttr('disabled');
							$petition_photo_input.css('display', 'block');
						} catch(e) {
							giverhubError({e:e});
						}
						return false;
					});

					$modal.on('keyup', '.add-url-input', function(event) {
						try {
							if (event.keyCode == 13) { // ENTER
								$modal.find('.add-url-btn').trigger('click');
							}
						} catch(e) {
							giverhubError({e:e});
						}
						return true;
					});

					$modal.modal('show');

				} catch(e) {
					giverhubError({e:e});
				}
				$this.button('reset');
				return false;
			});

			var check_email_regex = /^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,6}$/;
			$publish_success_modal.on('click', '.add-target-email-btn', function() {
				var $this = jQuery(this);
				var $add_target_email_input = $publish_success_modal.find('.add-target-email-input');
				try {
					$this.button('loading');

					var $publish_success_alert_danger = $publish_success_modal.find('.alert-danger');
					var $publish_success_alert_success = $publish_success_modal.find('.alert-success');
					$publish_success_alert_danger.addClass('hide');
					$publish_success_alert_success.addClass('hide');

					$add_target_email_input.attr('disabled', 'disabled');
					var email = $add_target_email_input.val().trim();

					if (!check_email_regex.test(email)) {
						$publish_success_alert_danger.find('.message').html('Email is invalid')
						$publish_success_alert_danger.removeClass('hide');
						$this.button('reset');
						$add_target_email_input.removeAttr('disabled');
						return false;
					}

					jQuery.ajax({
						url : '/giverhub_petition/add_email',
						type : 'post',
						dataType : 'json',
						data : {
							email : email,
							petition_id : $publish_success_modal.data('petition_id')
						},
						success : function(json) {
							try {
								if (json === undefined || !json || json.success === undefined || !json.success) {
									if (typeof(json.msg) === 'undefined') {
										giverhubError({msg : 'Bad response'});
									} else {
										$publish_success_alert_danger.find('.message').html(json.msg);
										$publish_success_alert_danger.removeClass('hide');
									}
								} else {
									$add_target_email_input.val('');
									$publish_success_alert_success.find('.email').html(email);
									$publish_success_alert_success.removeClass('hide');
								}
							} catch(e) {
								giverhubError({e:e});
							}
						},
						complete : function() {
							$this.button('reset');
							$add_target_email_input.removeAttr('disabled');
						},
						error : function() {
							giverhubError({msg : 'Request Failed'});
						}
					});
				} catch(e) {
					$this.button('reset');
					$add_target_email_input.removeAttr('disabled');
					giverhubError({e:e});
				}
				return false;
			});

			$publish_success_modal.on('keyup', '.add-target-email-input', function(event) {
				try {
					if (event.keyCode == 13) { // ENTER
						$publish_success_modal.find('.add-target-email-btn').trigger('click');
					}
				} catch(e) {
					giverhubError({e:e});
				}
				return true;
			});

			$publish_success_modal.on('click', '.btn-my-petitions', function() {
				try {
					var $this = jQuery(this);

					if (jQuery('main.my_petitions_page').length) {
						$publish_success_modal.modal('hide');
					} else {
						$this.button('loading');
						window.location = '/members/my_petitions';
					}
				} catch(e) {
					giverhubError({e:e});
				}
			});

			$publish_success_modal.on('click', '.btn-close-publish-success-modal', function() {
				try {
					$publish_success_modal.modal('hide');
					giverhubSuccess({msg :'<strong>Great!</strong> Please wait while you are being taken to your petition...'});
					window.location = $publish_success_modal.data('petition-url');
				} catch(e) {
					giverhubError({e:e});
				}
			});

			$publish_success_modal.on('click', '.share-petition-on-facebook', function() {
				try {
					var $this = jQuery(this);

					FB.ui({
						app_id: body.data('fb-app-id'),
						method: 'share',
						href: $this.data('petition-url')
					}, function(response){
						try {
							if (response.error_code !== undefined) {
								if (response.error_code == 4201) {
									// user canceled
								} else {
									giverhubError({msg : 'Facebook returned an error: ' + response.error_message + ' error_code: ' + response.error_code});
								}
							} else {
								giverhubSuccess({msg : 'Thank you for sharing the petition on facebook.'});
							}
						} catch(e) {
							giverhubError({e:e});
						}
					});
				} catch(e) {
					giverhubError({e:e});
				}
			});

			$publish_success_modal.on('click', '.btn-link-nonprofit', function(event) {
				try {
					var $link_nonprofit_container = $publish_success_modal.find('.link-nonprofit-container');
					var $link_nonprofit_text = $link_nonprofit_container.find('.link-nonprofit-text');
					$link_nonprofit_container.removeClass('hide');
					$link_nonprofit_text.focus();
					event.stopPropagation();
				} catch(e) {
					giverhubError({e:e});
				}
				return false;
			});

			$publish_success_modal.on('click', function() {
				try {
					$publish_success_modal.find('.link-nonprofit-results').addClass('hide');
				} catch(e) {
					giverhubError({e:e});
				}
			});

			$publish_success_modal.on('click', '.link-nonprofit-text', function(event) {
				try {
					jQuery(this).trigger('keyup');
					event.stopPropagation();
				} catch(e) {
					giverhubError({e:e});
				}
			});

			var KEY_UP = 38;
			var KEY_DOWN = 40;
			var KEY_ENTER = 13;
			var KEY_ESCAPE = 27;

			var charity_results_store = {};
			$publish_success_modal.on('keyup', '.link-nonprofit-text', function(event) {
				try {
					var $link_nonprofit_container = $publish_success_modal.find('.link-nonprofit-container');
					var $link_nonprofit_text = $link_nonprofit_container.find('.link-nonprofit-text');
					var $link_nonprofit_results = $link_nonprofit_container.find('.link-nonprofit-results');

					var $this = jQuery(this);
					var value = $this.val();

					if (event.keyCode == KEY_DOWN || event.keyCode == KEY_UP) {
						var $selected = $link_nonprofit_results.find('.selected');

						var $target;

						if (event.keyCode == KEY_DOWN) {
							$target = $selected.next('li');

							if (!$selected.length || !$target.length) {
								$target = $link_nonprofit_results.find('li').first();
							}
						} else {
							$target = $selected.prev('li');

							if (!$selected.length || !$target.length) {
								$target = $link_nonprofit_results.find('li').last();
							}
						}

						$selected.removeClass('selected');
						$target.addClass('selected');
						return false;
					}

					if (event.keyCode == KEY_ENTER) {
						$link_nonprofit_results.find('.selected a')[0].click();
						return false;
					}

					if (event.keyCode == KEY_ESCAPE) {
						$link_nonprofit_results.addClass('hide');
						return false;
					}

					if (!value.length) {
						$link_nonprofit_results.addClass('hide');
						return true;
					}
					$link_nonprofit_results.removeClass('hide');

					var res = charity_results_store[value];
					if (res === undefined) {
						$link_nonprofit_results.html('<img src="/images/ajax-loaders/ajax-loader.gif">');
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
											$link_nonprofit_results.html(json.results);
											$link_nonprofit_results.find('li').first().addClass('selected');
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
							$link_nonprofit_results.html('<img src="/images/ajax-loaders/ajax-loader.gif">');
						} else {
							$link_nonprofit_results.html(res['results']);
							$link_nonprofit_results.find('li').first().addClass('selected');
						}
					}
				} catch(e) {
					giverhubError({e:e});
				}
				return true;
			});

			$publish_success_modal.on('click', '.link-nonprofit-chosen .select-charity', function() {
				return false;
			});

			var clearCharityButtonString = '<button type="button" class="btn btn-xs btn-danger btn-clear-charity" data-loading-text="x">x</button>';

			$publish_success_modal.on('click', '.link-nonprofit-results .select-charity', function() {
				try {
					var $link_nonprofit_container = $publish_success_modal.find('.link-nonprofit-container');
					var $link_nonprofit_text = $link_nonprofit_container.find('.link-nonprofit-text');
					var $link_nonprofit_results = $link_nonprofit_container.find('.link-nonprofit-results');
					var $link_nonprofit_chosen = $link_nonprofit_container.find('.link-nonprofit-chosen');

					var $this = jQuery(this);
					var $li = $this.parent();
					var $newLi = $li.clone();

					var $a = $newLi.find('a');

					$a.html(jQuery('<span>'+$a.html()+'</span>'));
					$a.append(clearCharityButtonString);
					$link_nonprofit_text.addClass('hide');
					$link_nonprofit_chosen.html($newLi);
					$link_nonprofit_chosen.removeClass('hide');
					$link_nonprofit_results.addClass('hide');

					var $chosen_li = $link_nonprofit_chosen.find('li');

					var charity_id = $chosen_li.data('charity-id');
					var petition_id = $publish_success_modal.data('petition_id');

					$publish_success_modal.find('.saved-linked-nonprofit').addClass('hide');
					$publish_success_modal.find('.saving-linked-nonprofit').removeClass('hide');
					jQuery.ajax({
						url : '/giverhub_petition/link_nonprofit',
						type : 'post',
						data : {
							petition_id : petition_id,
							charity_id : charity_id
						},
						dataType : 'json',
						error : function() {
							$this.button('reset');
							giverhubError({msg : 'Request Failed'});
						},
						success : function(json) {
							try {
								if (typeof(json) === "undefined" || !json || typeof(json.success) === "undefined" || !json.success) {
									giverhubError({msg : 'Bad response'});
								} else {
									$publish_success_modal.find('.saving-linked-nonprofit').addClass('hide');
									$publish_success_modal.find('.saved-linked-nonprofit').removeClass('hide');
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

			$publish_success_modal.on('click', '.link-nonprofit-chosen .btn-clear-charity', function() {
				var $this = jQuery(this);
				try {
					$this.button('loading');

					var petition_id = $publish_success_modal.data('petition_id');

					jQuery.ajax({
						url : '/giverhub_petition/clear_nonprofit',
						type : 'post',
						dataType : 'json',
						data : { petition_id : petition_id },
						error : function() {
							giverhubError({msg : 'Request Failed. Try again.'});
						},
						complete : function() {
							$this.button('reset');
						},
						success : function(json) {
							try {
								if (typeof(json) !== "object" || typeof(json.success) !== "boolean" || !json.success) {
									giverhubError({msg : 'Bad response.'});
								} else {
									var $link_nonprofit_container = $publish_success_modal.find('.link-nonprofit-container');
									var $link_nonprofit_text = $link_nonprofit_container.find('.link-nonprofit-text');
									var $link_nonprofit_chosen = $link_nonprofit_container.find('.link-nonprofit-chosen');

									$link_nonprofit_chosen.addClass('hide');
									$link_nonprofit_chosen.html('');
									$link_nonprofit_text.val('').removeClass('hide');

									$publish_success_modal.find('.saving-linked-nonprofit').addClass('hide');
									$publish_success_modal.find('.saved-linked-nonprofit').addClass('hide');
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

			function show_sign_in_first_modal() {
				window.signInOrJoinFirst('You need to be signed in to sign petitions');
			}

			jQuery(document).on('click', '.sign-petition-reason-overview', function() {
				try {
					if (!body.data('signed-in')) {
						show_sign_in_first_modal();
						return false;
					}
				} catch(e) {
					giverhubError({e:e});
				}
			});

			jQuery(document).on('click', '.btn-hide-signature', function() {
				try {
					if (!body.data('signed-in')) {
						show_sign_in_first_modal();
						return false;
					}
				} catch(e) {
					giverhubError({e:e});
				}
			});

			function fb_share_petition(url, petition_id) {
				if (typeof(url) !== "string" || !url.length) {
					throw "invalid url:" + url;
				}
				var orig_petition_id = petition_id;
				petition_id = parseInt(petition_id);
				if (!petition_id) {
					throw "invalid petition_id: " + orig_petition_id;
				}

				FB.ui({
					app_id: body.data('fb-app-id'),
					method: 'share',
					href: url
				}, function (response) {
					if (response !== null) {
						jQuery.ajax({
							url : '/giverhub_petition/fb_share',
							data : {
								petition_id : petition_id
							},
							type : 'post',
							dataType : 'json'
						});
					}
				});
			}

			jQuery(document).on('click','.fb-share-g-petition-wrapper', function() {
				try {
					var $this = jQuery(this);
					fb_share_petition(document.URL, $this.data('petition-id'));
				} catch(e) {
					giverhubError({e:e});
				}
				return false;
			});

			jQuery(document).on('click', '.btn-sign-g-petition-overview', function() {
				var btn = jQuery(this);

				try {
					var petitionId = btn.data('petition-id');

					if (btn.hasClass('unsign')) {
						giverhubPrompt({
							msg : 'Are you sure that you want to remove your signature?',
							yes : function() {
								btn.button('loading');
								jQuery.ajax({
									url : '/giverhub_petition/unsign',
									data : { petitionId : petitionId },
									dataType : 'json',
									type : 'post',
									error : function() {
										giverhubError({msg : 'Request failed!'});
									},
									success : function(json) {
										try {
											checkSuccess(json);
											if (typeof(json.sign_block) !== "string") {
												giverhubError({msg : 'Bad response from the server!'});
											} else {
												jQuery('.g-petition-sign-block').html(json.sign_block);
												giverhubSuccess({msg : 'Your signature has been removed.'});
											}
										} catch(e) {
											giverhubError({e:e});
										}
									},
									complete : function() {
										btn.button('reset');
									}
								});
							}
						});

						return;
					}

					btn.button('loading');

					var $form = btn.closest('form');

					var reason = $form.find('.sign-petition-reason-overview').val().trim();

					var $sign_petition_hidden_overview = $form.find('.sign-petition-hidden-overview');
					var hidden;
					if ($sign_petition_hidden_overview.length) {
						hidden = $sign_petition_hidden_overview.is(':checked');
					} else {
						hidden = $form.find('.btn-hide-signature').hasClass('active');
					}



					var shareOnFacebook;
					var $share_petition_signature_on_facebook = $form.find('.share-petition-signature-on-facebook');
					if ($share_petition_signature_on_facebook.length) {
						shareOnFacebook = $share_petition_signature_on_facebook.is(':checked');
					}

					jQuery.ajax({
						url : '/giverhub_petition/sign',
						type : 'post',
						dataType : 'json',
						data : {
							reason : reason,
							hidden : hidden,
							petitionId : petitionId
						},
						error : function() {
							giverhubError({msg : 'Oops, request failed. We apologize and will work on resolving this issue so that you can sign petitions. Please try again later.'});
							btn.button('reset');
						},
						success : function(json) {
							try {
								if (!json || json === undefined || json.success === undefined || !json.success || typeof(json.sign_block) !== "string") {
									if (json.msg === undefined || !json.msg) {
										giverhubError({msg : 'Request failed. Bad response from server. Please try again later.'});
									} else {
										if (json.success == false) {
											giverhubError({subject: 'Sorry!', msg : 'You have already signed this petition.'});
										}
									}
									btn.button('reset');
								} else {
									jQuery('.g-petition-sign-block').html(json.sign_block);

									if (typeof(shareOnFacebook) === "undefined") {
										window.giverhubPrompt({
											msg : 'Do you want to share this petition on facebook?',
											yes: function() {
												FB.ui({
													app_id: body.data('fb-app-id'),
													method: 'share',
													href: btn.data('petition-url')
												}, function(response){
													if (response !== null) {
														jQuery.ajax({
															url : '/giverhub_petition/fb_share',
															data : {
																petition_id : petitionId
															},
															type : 'post',
															dataType : 'json'
														});
													}
													giverhubSuccess({msg : 'Thank you for signing the petition.'});
												});
											},
											no: function() {
												giverhubSuccess({msg : 'Thank you for signing the petition.'});
											}
										});
									} else if (shareOnFacebook) {
										FB.ui({
											app_id: body.data('fb-app-id'),
											method: 'share',
											href: btn.data('petition-url')
										}, function(response){
											if (response !== null) {
												jQuery.ajax({
													url : '/giverhub_petition/fb_share',
													data : {
														petition_id : petitionId
													},
													type : 'post',
													dataType : 'json'
												});
											}
											giverhubSuccess({msg : 'Thank you for signing the petition.'});
										});
									} else {
										giverhubSuccess({msg : 'Thank you for signing the petition.'});
									}
								}
							} catch (e) {
								giverhubError({e:e});
							}
						}
					})
				} catch(e) {
					btn.button('reset');
					giverhubError({e:e});
				}

			});
			
			jQuery(document).on('click', '.g-petition-hide-signature', function() {
				$("#sign-petition-hidden-overview").prop("checked",true);
				$('.g-petition-hide-signature').css('display','none');
				$('.g-petition-hidden-signature').css('display','block');
			});

			jQuery(document).on('click', '.g-petition-share-signature', function() {
				$('.share-petition-signature-on-facebook').attr('checked', true);
				$('.g-petition-share-signature').css('display','none');
				$('.g-petition-donot-share').css('display','block');
			});

			jQuery(document).on('click', '.g-petition-content-edit-icon', function() {
				var $gPetitionInformationEdit = $('.g-petition-information-edit');
				var $gPetitionInformation	  = $('.g-petition-information');
				$gPetitionInformation.css('display','none');
				$gPetitionInformationEdit.css('display','block');

				$gPetitionInformationEdit.find('.why-text-edit').summernote({
					toolbar: [
						['style', ['bold', 'italic', 'underline']],
						['para', ['ul', 'ol', 'paragraph']]
					]
				});
				$gPetitionInformationEdit.find('.note-editable').attr('data-placeholder', $gPetitionInformationEdit.find('.why-text-edit').attr('placeholder'));
			});

			var gPetitionGoalInput = jQuery('#g-petition-goal-input');
			jQuery(document).on('keyup', '#g-petition-goal-input', function() {
				try {
					var start = this.selectionStart;
					var end = this.selectionEnd;
					gPetitionGoalInput.val(gPetitionGoalInput.val().replace(/[^0-9$]/g, ''));
					this.setSelectionRange(start, end);
				} catch(e) {
					giverhubError({e:e});
				}
				return true;
			});			

			var addGiverhubPetitionNewsModal 	= jQuery('#add-g-petition-news-modal');
			var addGiverhubPetitionGoalModal 	= jQuery('#add-g-petition-goal-modal');
			var addGiverhubPetitionDeadlineModal= jQuery('#add-g-petition-deadline-modal');
			var editGiverhubPetitionMediaModal	= jQuery('#edit-g-petition-media-modal');
			var petitionIdInput					= $('#g-petition-input-id');
			var giverhubPetitionGoal			= $('#g-petition-goal-input');
			var giverhubPetitionNews 			= $('#giverhub-petition-news');
			var giverhubPetitionDeadline		= $('#g-petition-deadline-date');

			jQuery(document).on('click', '.add_petition_news', function() {
				addGiverhubPetitionNewsModal.modal('show');
			});
			jQuery(document).on('click', '.btn-cancel-g-petition-news', function() {
				addGiverhubPetitionNewsModal.modal('hide');
			});
			
			jQuery(document).on('click', '.add_goal_header_link', function() {
				addGiverhubPetitionGoalModal.modal('show');
			});
			jQuery(document).on('click', '.btn-cancel-g-petition-goal', function() {
				addGiverhubPetitionGoalModal.modal('hide');
			});

			jQuery(document).on('click', '.add_deadline_header_link', function() {
				addGiverhubPetitionDeadlineModal.modal('show');
			});
			jQuery(document).on('click', '.btn-cancel-g-petition-deadline', function() {
				addGiverhubPetitionDeadlineModal.modal('hide');
			});
	
			jQuery(document).on('click', '.btn-add-g-petition-goal', function() {
				var btn = jQuery(this);
				try {
					var goal = giverhubPetitionGoal.val().trim();
					var petitionId = petitionIdInput.val().trim();
					if (!goal.length) {
						giverhubError({msg : 'You need to enter goal.'});
						goal.focus();
						return false;
					}
					if (!petitionId.length) {
						giverhubError({msg : 'Something went wrong.'});
						return false;
					}
					btn.button('loading');
					jQuery.ajax({
						url : '/giverhub_petition/save_goal',
						type : 'post',
						dataType : 'json',
						data : { goal : goal, petitionId : petitionId},
						error : function() {
							giverhubError({msg : 'Request Failed. Please try again later. Thank you for being patient.'})
						},
						success : function(json) {
							try {
								if (!json || json === undefined || json.success === undefined || !json.success || typeof(json.goal_html) !== "string") {
									giverhubError({msg : 'Bad response. Sorry, please try again later.'});
								} else {
									addGiverhubPetitionGoalModal.modal('hide');
									jQuery('.g-petition-goal-wrapper').html(json.goal_html);
									giverhubSuccess({subject : 'Thank you!', msg : 'Goal for your Petition has been set successfully!'});
								}
							} catch(e) {
								giverhubError({msg : 'Invalid response from server. Sorry, please try again later.'})
							}
						},
						complete : function() {
							$('.add-goal-img').text(goal);
							btn.button('reset');
						}
					});

				} catch(e) {
					giverhubError({e:e});
				}
			});

			jQuery(document).on('click', '.btn-add-g-petition-deadline', function() {

				var btn = jQuery(this);
				try {
					var deadline = giverhubPetitionDeadline.val().trim();
					var petitionId = petitionIdInput.val().trim();
					if (!deadline.length) {
						giverhubError({msg : 'You need to enter/select deadline.'});
						deadline.focus();
						return false;
					}
					if (!petitionId.length) {
						giverhubError({msg : 'Something went wrong.'});
						return false;
					}
					btn.button('loading');
					jQuery.ajax({
						url : '/giverhub_petition/save_deadline',
						type : 'post',
						dataType : 'json',
						data : { deadline : deadline, petitionId : petitionId},
						error : function() {
							giverhubError({msg : 'Request Failed. Please try again later. Thank you for being patient.'})
						},
						success : function(json) {
							try {
								if (!json || json === undefined || json.success === undefined || !json.success) {
									giverhubError({msg : 'Bad response. Sorry, please try again later.'});
								} else {
									addGiverhubPetitionDeadlineModal.modal('hide');
									jQuery('.g-petition-deadline-wrapper').html(json.deadline);
									giverhubSuccess({subject : 'Thank you!', msg : 'Deadline for your Petition has been set successfully!'});
								}
							} catch(e) {
								giverhubError({msg : 'Invalid response from server. Sorry, please try again later.'})
							}
						},
						complete : function() {
							btn.button('reset');
						}
					});

				} catch(e) {
					giverhubError({e:e});
				}
			});

			
			jQuery(document).on('click', '.btn-add-g-petition-news', function() {

				var btn = jQuery(this);
				try {
					var news = giverhubPetitionNews.val().trim();
					var petitionId = petitionIdInput.val().trim();
					if (!news.length) {
						giverhubError({subject : 'Missing news', msg : 'You need to enter news'});
						giverhubError.hideEvent = function() {giverhubPetitionNews.focus()};
						news.focus();
						return false;
					}
					if (!petitionId.length) {
						giverhubError({msg : 'Something went wrong.'});
						return false;
					}
				
					btn.button('loading');
					jQuery.ajax({
						url : '/giverhub_petition/save_news',
						type : 'post',
						dataType : 'json',
						data : { news : news, petitionId : petitionId},
						error : function() {
							giverhubError({msg : 'Request Failed. Please try again later. Thank you for being patient.'})
						},
						success : function(json) {
							try {
								if (!json || json === undefined || json.success === undefined || !json.success) {
									giverhubError({msg : 'Bad response. Sorry, please try again later.'});
								} else {
									addGiverhubPetitionNewsModal.modal('hide');
									giverhubSuccess({subject : 'Thank you!', msg : 'News for your Petition has been added successfully!'});
								}
							} catch(e) {
								giverhubError({msg : 'Invalid response from server. Sorry, please try again later.'})
							}
						},
						complete : function() {
							btn.button('reset');
						}
					});

				} catch(e) {
					giverhubError({e:e});
				}
			});


			jQuery(document).on('click', '.btn-update-g-petition', function() {

				var $gPetitionInformationEdit = $('.g-petition-information-edit');
				var $this = jQuery(this);
				try {
					$this.button('loading');
					var petition = validate_update_form($gPetitionInformationEdit);
					if (petition === false) {
						$this.button('reset');
					} else {
						update_petition({
							petition: petition,
							error : function() {},
							complete : function() {
								$this.button('reset');
							},
							success : function() {
								$modal.modal('hide');
							}
						});
					}
				} catch(e) {
					$this.button('reset');
					giverhubError({e:e});
				}
				return false;
			});

			function update_petition(params) {				
				jQuery.ajax({
					url : '/giverhub_petition/update',
					type : 'post',
					dataType : 'json',
					data : params.petition,
					success : function(json) {
						try {
							if (!json || json === undefined || json.success === undefined || !json.success || json.petition === undefined) {
								
								giverhubError({msg : 'Bad response from server.'});
							} else {
								
								
								giverhubSuccess({subject : 'Thankyou!', msg : 'You have successfully updated your petition'});
							}
						} catch(e) {alert('fail');
							params.error();
							giverhubError({e:e});
						}
					},
					error : function() {
						giverhubError({msg : 'Request Failed'});
						try {
							params.error();
						} catch(e) {
							giverhubError({e:e});
						}
					},
					complete : function() {
						try {
							params.complete();
						} catch(e) {
							giverhubError({e:e});
						}
					}
				});
			}

			function create_update_form_error($input, msg) {
				
				var $row = $input.closest('.row');
				var $alert = $row.find('.alert');
				var $message = $alert.find('.message');
				$row.find('.alert-danger').css('display','block');
				$message.html(msg);
				$row.addClass('has-error');
				$input.focus();
			}

			function validate_update_form($modal) {
				
				$modal.find('.has-error').removeClass('has-error');
			    $('.g-petition-information-edit').find('.alert-danger').css('display','none');
				var has_errors = false;

				var $target = $modal.find('input.target');
				var target_val = $target.val().trim();
				if (!target_val.length) {
					create_update_form_error($target, '<strong>Oops!</strong> Target is missing!');
					has_errors = true;
				} else if (target_val.length < 2) {
					create_update_form_error($target, 'Target is too short. minimum 2 characters!');
					has_errors = true;
				}				

				var $why = $modal.find('textarea.why');
				var why_val = $why.code().trim();
				if (!why_val.length) {
					create_update_form_error($why, '<strong>Oops!</strong> Why is missing!');
					has_errors = true;
				}

				var $what = $modal.find('input.what');
				var what_val = $what.val().trim();
				if (what_val.length < 8) {
					create_update_form_error($what, 'How is too short. minimum 8 characters!');
					has_errors = true;
				}
				if (!what_val.length) {
					create_update_form_error($what, '<strong>Oops!</strong> How is missing!');
					has_errors = true;
				}			

				if (has_errors) {
					return false;
				}

				var petitionId = $modal.find('#store-petition-id').val().trim();
				var petition = {
					target : target_val,
					what : what_val,
					why : why_val,
					petitionId : petitionId
				};

				return petition;

			}

			jQuery(document).on('click', '.g-petition-image-edit-icon', function() {
				editGiverhubPetitionMediaModal.modal('show');
				
			});

			function confirmGiverhubPetitionMediaRemove() {
				return confirm('Are you sure that you want to remove media for this Petition?');
			}

			jQuery(document).on('click', '.g-petition-remove-media', function() {		
				var success = confirmGiverhubPetitionMediaRemove();
				
				//Remove media for Giverhub Petition
				if (success) {
					var petitionId = petitionIdInput.val().trim();
					var mediaType  = $('#giverhub-petition-media-type').val().trim();
					jQuery.ajax({
						url : '/giverhub_petition/remove_media',
						type : 'post',
						dataType : 'json',
						data : { petitionId : petitionId, mediaType : mediaType},
						error : function() {
							giverhubError({msg : 'Request Failed. Please try again later. Thank you for being patient.'})
						},
						success : function(json) {
							try {
								if (!json || json === undefined || json.success === undefined || !json.success) {
									giverhubError({msg : 'Bad response. Sorry, please try again later.'});
								} else {
									editGiverhubPetitionMediaModal.modal('hide');
									giverhubSuccess({subject : 'Thank you!', msg : 'You have successfully removed media for your petition!<br><br>Reloading ...'});
									location.reload(true);
								}
							} catch(e) {
								giverhubError({msg : 'Invalid response from server. Sorry, please try again later.'})
							}
						},
						complete : function() {
						}
					});
				}

			});

			var gPetitionMediaDisplayArea = $('.display-area');
			var gPetitionMediaUpdateArea = $('.update-area');
			jQuery(document).on('click', '.g-petition-update-media', function() {			
				gPetitionMediaUpdateArea.css('display','block');
				gPetitionMediaDisplayArea.css('display','none');

			});

			//Code to update Giverhub petition media
			var $g_add_url_container = editGiverhubPetitionMediaModal.find('.add-url-container');
			var $g_add_url_input = $g_add_url_container.find('.add-url-input');
			var $g_add_url_preview = editGiverhubPetitionMediaModal.find('.add-url-preview');
			var $g_add_url_alert = editGiverhubPetitionMediaModal.find('.add-url-alert');
			var $g_add_url_btn = editGiverhubPetitionMediaModal.find('.add-url-btn');
			var $gPetitionId = editGiverhubPetitionMediaModal.find('.g-petition-id');
			var $g_btn_delete_preview = jQuery('<button type="button" class="btn btn-danger btn-xs btn-delete-preview-url">x</button>');

			var $g_upl_btn = editGiverhubPetitionMediaModal.find('.upl-btn');

			var $g_upload_photo_btn = $g_upl_btn.find('.upload-photo-btn');
			var $g_petition_photo_input = $g_upl_btn.find('.petition-photo-input');

			var $g_upl_loading = editGiverhubPetitionMediaModal.find('.upl-loading');
			var $g_upl_img = editGiverhubPetitionMediaModal.find('.upl-img');

			function fileUploadError(msg) {
				$g_upl_btn.removeClass('hide');
				$g_upl_loading.addClass('hide');
				$g_upl_img.addClass('hide');
				giverhubError({msg : msg });
			}

			editGiverhubPetitionMediaModal.find('form')
				.fileupload()
				.bind(
					'fileuploaddone',
					function(a,b) {
						try {
							var d = jQuery.parseJSON(b.result);
							switch(d[0].error) {
								case undefined:
									$g_upl_loading.addClass('hide');
									$g_upl_img.find('img').attr('src', d[0].thumbnail_url);
									$g_upl_img.data('photo-id', d[0].photo_id);
									$g_upl_img.data('photo-src', d[0].thumbnail_url);
									$g_upl_img.removeClass('hide');
									$g_add_url_input.attr('disabled', 'disabled');
									$g_add_url_btn.attr('disabled', 'disabled');
									break;
								case 'acceptFileTypes':
									fileUploadError('Bad File Type. We only accept images, such as jpg, png and gif.');
									break;
								default:
									fileUploadError('Unexpected Error: ' + d[0].error);
									break;
							}

						} catch(e) {alert(e);
							giverhubError({e:e});
						}
					}
				)
				.bind(
					'fileuploadstart',
					function() {
						$g_upl_btn.addClass('hide');
						$g_upl_loading.removeClass('hide');
					}
				)
				.bind(
					'fileuploadfail',
					function(a,b) {
						fileUploadError('Something went wrong when uploading the file."' + b.errorThrown + '". Notice that files bigger than 10Mb should not be uploaded. Thank you for your patience.');
					}
				);

			editGiverhubPetitionMediaModal.on('click', '.btn-delete-create-giverhub-petition-image', function() {
				var $this = jQuery(this);
				try {
					$this.button('loading');

					var tempId = editGiverhubPetitionMediaModal.find('.temp-id').val();

					jQuery.ajax({
						url : '/upload/delete_petition_create_image',
						type : 'post',
						dataType : 'json',
						data : { tempId : tempId },
						error : function() {
							giverhubError({msg : 'Request Failed!'});
						},
						success : function(json) {
							try {
								if (json === undefined || !json || json.success === undefined || !json.success) {
									giverhubError({msg : 'Bad response from server!'});
								} else {
									$g_upl_img.addClass('hide');
									$g_upl_btn.removeClass('hide');
									$g_add_url_input.removeAttr('disabled');
									$g_add_url_btn.removeAttr('disabled');
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
			});

			editGiverhubPetitionMediaModal.on('click', '.btn-update-g-petition-media', function() {
				var $this = jQuery(this);
				try {
					$this.button('loading');
					var petition = petition_media_edit_form(editGiverhubPetitionMediaModal);
					update_giverhub_petition_media({
						petition: petition,
						error : function() {},
						complete : function() {
							$this.button('reset');
						},
						success : function() {
							$modal.modal('hide');
						}
					});
				} catch(e) {
					$this.button('reset');
					giverhubError({e:e});
				}
				return false;
			});

			function petition_media_edit_form(editGiverhubPetitionMediaModal) {
				var petitionIdEdit = $gPetitionId.val().trim();
				var petition = {
					petition_id : petitionIdEdit,
				};

				var $g_upl_img = editGiverhubPetitionMediaModal.find('.upl-img');
				if (!$g_upl_img.hasClass('hide')) {
					petition.photo_id = $g_upl_img.data('photo-id');
					petition.photo_src = $g_upl_img.data('photo-src');
					//petition.photo_src = $g_upl_img.attr('src');
				}
				
				var $g_add_url_preview = editGiverhubPetitionMediaModal.find('.add-url-preview');
				if (!$g_add_url_preview.hasClass('hide')) {
					if ($g_add_url_preview.hasClass('img')) {
						petition.img_url = $g_add_url_preview.data('img-url');
					} else if ($g_add_url_preview.hasClass('youtube-vid')) {
						petition.video_id = $g_add_url_preview.data('video-id');
					}
				}

				if (!petition.photo_id && !petition.img_url && !petition.video_id) {
					petition.no_media = jQuery("div.giverhub-petition-no-media-block").html();
				}				
				petition.giverhub_petition_added_by = $("div.giverhub-petition-added-by-block").html();
				return petition;
			}

			function update_giverhub_petition_media(params) {
				jQuery.ajax({
					url : '/giverhub_petition/update_g_petition_media',
					type : 'post',
					dataType : 'json',
					data : params.petition,
					success : function(json) {
						try {
							if (!json || json === undefined || json.success === undefined || !json.success || json.petition === undefined) {
								params.error();
								giverhubError({msg : 'Bad response from server.'});
							} else {
								editGiverhubPetitionMediaModal.modal('hide');
								giverhubSuccess({msg : 'You have successfully updated media for your petition.<br><br>The page is being reloaded...'});
								location.reload(true);
							}
						} catch(e) {
							giverhubError({e:e});
						}
					},
					error : function() {
						giverhubError({msg : 'Request Failed'});
					},
					complete : function() {
						try {
							params.complete();
						} catch(e) {
							giverhubError({e:e});
						}
					}
				});
			}

			editGiverhubPetitionMediaModal.on('click', '.add-url-btn', function() {
					var $this = jQuery(this);
					try {
						$this.button('loading');
						$g_add_url_alert.addClass('hide');
						var url = $g_add_url_input.val().trim();

						if (!url.length) {
							add_url_error(editGiverhubPetitionMediaModal, 'Enter an url please.');
							$this.button('reset');
							return false;
						}
						var has_video = false;
						var $g_add_url_preview = editGiverhubPetitionMediaModal.find('.add-url-preview');
						if (url.indexOf('youtube.com') != -1) {
							uri = window.parseUri(url);
							if (uri && uri.queryKey && uri.queryKey.v) {
								$g_add_url_container.addClass('hide');
								var video_id = uri.queryKey.v;
								$g_add_url_preview.html(
									'<iframe ' +
										'class="youtube-player youtube-preview-iframe" ' +
										'type="text/html" ' +
										'width="100%" ' +
										'height="" ' +
										'src="https://www.youtube.com/embed/'+video_id+'" ' +
										'allowfullscreen ' +
										'frameborder="0">' +
									'</iframe>'
								).append($g_btn_delete_preview).addClass('youtube-vid').removeClass('img').data('video-id', video_id).removeClass('hide');
								has_video = true;

								$g_upload_photo_btn.attr('disabled', 'disabled');
								$g_petition_photo_input.css('display', 'none');

								$this.button('reset');
							}
						}

						if (!has_video) {
							var $img = jQuery('<img/>');
							$img.bind({
								load: function() {
									$g_add_url_container.addClass('hide');
									$g_add_url_preview.addClass('img').removeClass('youtube-vid').data('img-url', url).removeClass('hide');

									$g_upload_photo_btn.attr('disabled', 'disabled');
									$g_petition_photo_input.css('display', 'none');

									$this.button('reset');
								},
								error: function() {
									add_url_error(editGiverhubPetitionMediaModal, 'The url you entered could not be loaded.');
									$this.button('reset');
								}
							});

							$g_add_url_preview.html($img);
							$g_add_url_preview.append($g_btn_delete_preview);
							$img.attr('src',url);
						}

					} catch(e) {
						$this.button('reset');
						giverhubError({e:e});
					}
					return false;
				});

				editGiverhubPetitionMediaModal.on('click', '.btn-delete-preview-url', function() {
					try {
						$g_add_url_preview.addClass('hide').removeClass('img').removeClass('youtube-vid');
						$g_add_url_alert.addClass('hide');
						$g_add_url_input.val('');
						$g_add_url_container.removeClass('hide');

						$g_upload_photo_btn.removeAttr('disabled');
						$g_petition_photo_input.css('display', 'block');
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
