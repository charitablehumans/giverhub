try {
	jQuery(document).ready(function() {
		try {
			var $post_buttons_container = jQuery('#post-buttons-container');
			var $activity_feed_textarea = jQuery('.activity-feed-textarea');
			var $link_nonprofit_container = jQuery('#link-nonprofit-container');
			var $link_nonprofit_results = jQuery('#link-nonprofit-results');
			var $link_nonprofit_chosen = jQuery('#link-nonprofit-chosen');
			var $link_nonprofit_text = jQuery('#link-nonprofit-text');
			var $youtube_preview_container = jQuery('#youtube-previews-container');
			var $activity_feed_url_previews_container = jQuery('#activity-feed-url-previews-container');
			var $activity_feed_share_facebook_checkbox = jQuery('#activity-feed-share-facebook-button');

			var load_external_url_timer;
			$activity_feed_textarea.on('input', function() {
				try {
					if (typeof(load_external_url_timer) !== "undefined") {
						clearInterval(load_external_url_timer);
						load_external_url_timer = undefined;
					}
					var text = $activity_feed_textarea.val();
					var pieces = text.split(/\s/);
					var youtube_video_ids = [];
					var urls = [];
					jQuery.each(pieces, function(i,piece) {
						var trimmed = piece.trim();
						var uri = window.parseUri(trimmed);
						if (uri && uri && uri.queryKey && uri.queryKey.v && trimmed.indexOf('youtube.com') != -1) {
							youtube_video_ids.push(uri.queryKey.v);
						} else if(uri && uri.protocol) {
							urls.push(trimmed);
						}
					});

					var url;
					if (urls.length) {
						url = urls[0];
					}

					if (youtube_video_ids.length) {
						jQuery.each(youtube_video_ids, function(i,video_id) {
							var container_id = 'youtube_preview_' + video_id;
							var $exists = jQuery('#' + container_id);
							if (!$exists.length) {
								$youtube_preview_container.append(
									jQuery(
										'<div ' +
											'id="' + container_id + '" ' +
											'class="youtube-preview-div" ' +
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
											'<button type="button" class="btn btn-danger btn-xs btn-delete-youtube-vid">x</button>' +
										'</div>'
									)
								);
							}
						});
					} else if (typeof(url) === "string" && url.length && $activity_feed_url_previews_container.data('url') != url) {
						load_external_url_timer = setTimeout(function() {
							try {
								$activity_feed_url_previews_container.html('<img src="/images/ajax-loaders/ajax-loader2.gif">').removeClass('hide').data('external-url-id', null);
								jQuery.ajax({
									url : '/post/external_url',
									type : 'post',
									dataType : 'json',
									data : {
										url : url
									},
									error : function() {
										giverhubError({msg : 'Request Failed'});
									},
									success : function(json) {
										try {
											if (typeof(json) !== "object" || typeof(json.success) !== "boolean" || !json.success || typeof(json.url) !== "string" || typeof(json.url_id) === "undefined") {
												$activity_feed_url_previews_container.html('').addClass('hide').data('external-url-id', null);
												if (typeof(json.invalid_url) === "boolean" && json.invalid_url) {
													$activity_feed_url_previews_container
														.data('url', json.url)
														.data('external-url-id', null);
												} else {
													giverhubError({msg : 'Bad response.'});
												}
											} else {
												$activity_feed_url_previews_container
													.data('url', json.url)
													.data('external-url-id', json.url_id)
													.html(
														(json.image_url ? '<img class="image '+(json.image_size === "large" ? "large" : '')+'" src="'+json.image_url+'">' : '') +
														'<div class="content-wrapper">' +
															'<p class="title">'+(json.content_type=='image'?'Image':json.title)+'</p>' +
															'<p class="desc">'+(json.content_type=='image'?'-':json.description)+'</p>' +
															'<p class="url">'+json.url+'</p>' +
														'</div>' +
														'<a href="#" class="delete-external-url">x</a>'
													);
											}
										} catch(e) {
											$activity_feed_url_previews_container.html('').addClass('hide').data('external-url-id', null);
											giverhubError({e:e});
										}
									}
								});
							} catch(e) {
								$activity_feed_url_previews_container.html('').addClass('hide').data('external-url-id', null);
								giverhubError({e:e});
							}
						},1000);
					}
				} catch(e) {
					giverhubError({e:e});
				}
			});

			$activity_feed_url_previews_container.on('click', '.delete-external-url', function() {
				try {
					$activity_feed_url_previews_container.html('').addClass('hide').data('external-url-id', null);
				} catch(e) {
					giverhubError({e:e});
				}
				return false;
			});

			jQuery('.make-post-comment-container').on('click', '.delete-external-url', function() {
				try {
					var $this = jQuery(this);
					$this.parent().html('').addClass('hide').data('external-url-id', null);
				} catch(e) {
					giverhubError({e:e});
				}
				return false;
			});

			$youtube_preview_container.on('click', '.btn-delete-youtube-vid', function() {
				try {
					var $container = jQuery(this).parent();
					$container.addClass('hide');
				} catch(e) {
					giverhubError({e:e});
				}
			});

			$activity_feed_textarea.on('focus', function() {
				try {
					$post_buttons_container.removeClass('hide');
				} catch(e) {
					giverhubError({e:e});
				}
			});

			$post_buttons_container.on('click', '.btn-activity-link-nonprofit', function(event) {
				try {
					$link_nonprofit_container.removeClass('hide');
					$link_nonprofit_text.focus();
					event.stopPropagation();
				} catch(e) {
					giverhubError({e:e});
				}
				return false;
			});

			jQuery(document).on('click', function() {
				try {
					$link_nonprofit_results.addClass('hide');
				} catch(e) {
					giverhubError({e:e});
				}
			});

			$post_buttons_container.on('click', '#link-nonprofit-text', function(event) {
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
			$post_buttons_container.on('keyup', '#link-nonprofit-text', function(event) {
				try {
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

			$link_nonprofit_chosen.on('click', '.select-charity', function() {
				return false;
			});

			var clearCharityButtonString = '<button type="button" class="btn btn-xs btn-danger btn-clear-charity">x</button>';

			$link_nonprofit_results.on('click', '.select-charity', function() {
				try {
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
				} catch(e) {
					giverhubError({e:e});
				}
				return false;
			});

			$link_nonprofit_chosen.on('click', '.btn-clear-charity', function() {
				try {
					$link_nonprofit_chosen.addClass('hide');
					$link_nonprofit_chosen.html('');
					$link_nonprofit_text.val('').removeClass('hide');
				} catch(e) {
					giverhubError({e:e});
				}
				return false;
			});

			jQuery(document).on('click', '.btn-post-activity-feed', function() {
				var $btn = jQuery(this);
				try {
					var $youtube_videos = $youtube_preview_container.find('.youtube-preview-div');
					var youtube_video_ids = [];
					$youtube_videos.each(function(i,e) {
						var $video = jQuery(e);
						if (!$video.hasClass('hide')) {
							youtube_video_ids.push($video.data('youtube-video-id'));
						}
					});

					var external_url_id = $activity_feed_url_previews_container.data('external-url-id');

					var $images = jQuery('#activity-feed-post-images-container').find('.image_container');

					var $chosen_li = $link_nonprofit_chosen.find('li');

					var $text = jQuery('.activity-feed-textarea');
					var text = $text.val();

					if (!youtube_video_ids.length && !$images.length && !$chosen_li.length && !text.length) {
						giverhubError({subject : 'Missing text', msg : 'You need to type some text first. You cannot post empty posts.'});
						return false;
					}

					if (text.length > 10000) {
						giverhubError({subject : 'Too much text', msg : 'Max 10000 characters. You typed '+text.length});
						return false;
					}

					var to_user_id = $btn.data('to-user-id');
					var context = $btn.data('context');

					var charity_id = $chosen_li.length ? $chosen_li.data('charity-id') : null;

					$btn.button('loading');

					var $temp_id = jQuery('#upload-activity-post-image-temp-id');

					jQuery.ajax({
						url : '/members/post_to_activity_feed',
						type : 'post',
						dataType : 'json',
						data : {
							to_user_id : to_user_id,
							text : text,
							context : context,
							charity_id : charity_id,
							temp_id : $temp_id.val() ? $temp_id.val() : '',
							youtube_video_ids : youtube_video_ids.length ? youtube_video_ids : null,
							external_url_id : external_url_id
						},
						error : function() {
							giverhubError({msg : 'Request failed.'});
						},
						success : function(json) {
							try {
								if (!json || json === undefined || json.success === undefined || !json.success || json.html === undefined || json.temp_id === undefined || json.post_id === undefined) {
									if (json.message !== undefined) {
										giverhubError({subject: 'Problem', msg : json.message});
									} else {
										giverhubError({msg : 'Bad response from server.'});
									}
								} else {
									$activity_feed_url_previews_container.data('external-url-id', null).addClass('hide').html('');

									var facebook_share = $activity_feed_share_facebook_checkbox.prop('checked');

									var $html = jQuery(json.html);

									$html.find('.instant-donation-switch').each(function(i,e) {
										window.enableSwitch(e);
									});
									jQuery('.activity-table-tbody').prepend($html);
									FB.XFBML.parse();
									$text.val('');
									$link_nonprofit_container.addClass('hide');
									$link_nonprofit_chosen.addClass('hide').html('');
									$link_nonprofit_text.val('').removeClass('hide');
									$link_nonprofit_container.addClass('hide');
									$temp_id.val(json.temp_id);
									jQuery('#activity-feed-post-images-container').html('');

									$youtube_preview_container.html('');

									if (facebook_share) {
										FB.ui({
											method: 'share_open_graph',
											action_type: 'giverhub:post',
											action_properties: JSON.stringify({
												story: body.data('base-url') + 'post/' + json.post_id
											})
										}, function(response){});
									}


									var $activity_load_more = jQuery('.activity-load-more');
									$activity_load_more.data('offset', parseInt($activity_load_more.data('offset'))+1);
								}
							} catch(e) {
								giverhubError({e:e});
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

			jQuery(document).on('click', '.btn-post-activity-feed-post-on-facebook', function() {
				try {
					FB.ui({
						method: 'share_open_graph',
						action_type: 'giverhub:post',
						action_properties: JSON.stringify({
							story: jQuery(this).data('post-url')
						})
					}, function(response){});
				} catch(e) {
					giverhubError({e:e});
				}
				return false;
			});

			jQuery('#upload-activity-post-image-form')
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
									$image_container.append(jQuery('<button type="button" data-loading-text="X" data-activity-feed-post-image-id="'+d[0].activity_feed_post_image_id+'" class="btn btn-xs btn-danger delete_image_button">X</button>'));
									jQuery('#activity-feed-post-images-container').removeClass('hide').append($image_container);
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
						jQuery('#activity-feed-post-images-container').addClass('hide');
						jQuery('#upload-activity-feed-post-image-loading').removeClass('hide');
						jQuery('#upload-activity-feed-post-image-container').removeClass('hide');
					}
				)
				.bind(
					'fileuploadstop',
					function() {
						jQuery('#upload-activity-feed-post-image-loading').addClass('hide');
					}
				)
				.bind(
					'fileuploadfail',
					function(a,b) {
						giverhubError({msg : 'Something went wrong when uploading the file."' + b.errorThrown + '". Notice that files bigger than 10Mb should not be uploaded. Thank you for your patience.'});
						jQuery('#activity-feed-post-images-container').addClass('hide').html('');
						jQuery('#upload-activity-feed-post-image-loading').addClass('hide');
						jQuery('#upload-activity-feed-post-image-container').addClass('hide');
					}
				);

			jQuery('#activity-feed-post-images-container').on('click', '.delete_image_button', function() {
				var $this = jQuery(this);
				try {
					var activity_feed_post_image_id = $this.data('activity-feed-post-image-id');

					$this.button('loading');
					jQuery.ajax({
						url : '/upload/delete_activity_feed_post_image',
						type : 'post',
						dataType : 'json',
						data : {
							activity_feed_post_image_id : activity_feed_post_image_id
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

			jQuery(document).on('click', '.btn-hide-activity', function() {
				var $this = jQuery(this);
				try {
					$this.button('loading');
					var id = $this.data('activity-id');
					var type = $this.data('activity-type');
					jQuery.ajax({
						url : '/members/hide_activity',
						type : 'post',
						dataType : 'json',
						data : { id : id, type : type },
						error : function() {
							giverhubError({msg : 'Request Failed.'});
						},
						success : function(json) {
							try {
								if (!json || json === undefined || json.success === undefined || !json.success) {
									giverhubError({msg : 'Bad Response'});
								} else {
									var $activity = $this.closest('.hide-activity');
									var button = '<button ' +
										'type="button" ' +
										'data-activity-id="' + id + '" ' +
										'data-activity-type="' + type + '" ' +
										'data-loading-text="Undoing..." ' +
										'class="btn btn-sm btn-primary btn-undo-hide-activity">Undo Hide</button>';
									var html = button;
									if ($activity.hasClass('two-col')) {
										html = '<td colspan="2">' + button + '</td>';
									}
									$activity.html(html);
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

			jQuery(document).on('click', '.btn-undo-hide-activity', function() {
				var $this = jQuery(this);
				try {
					$this.button('loading');
					var id = $this.data('activity-id');
					var type = $this.data('activity-type');

					jQuery.ajax({
						url : '/members/undo_hide_activity',
						dataType : 'json',
						type : 'post',
						data : { id : id, type : type },
						error : function() {
							giverhubError({msg : 'Request Failed.'});
						},
						complete : function() {
							$this.button('reset');
						},
						success : function(json) {
							try {
								if (!json || json === undefined || json.success === undefined || !json.success || json.html === undefined) {
									giverhubError({msg : 'Bad Response'});
								} else {
									var $tr = $this.closest('tr');

									var $html = jQuery(json.html);

									$html.find('.instant-donation-switch').each(function(i,e) {
										window.enableSwitch(e);
									});

									$tr.replaceWith($html);

									FB.XFBML.parse();
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

			jQuery(document).on('click', '.btn-hide-activity-post', function() {
				var $this = jQuery(this);
				try {
					if (!confirm('Are you sure that you want to hide this post?')) {
						return false;
					}
					$this.button('loading');
					var post_id = $this.data('activity-post-id');
					jQuery.ajax({
						url : '/members/hide_activity_feed_post',
						type : 'post',
						dataType : 'json',
						data : { post_id : post_id },
						error : function() {
							giverhubError({msg : 'Request Failed.'});
						},
						success : function(json) {
							try {
								if (!json || json === undefined || json.success === undefined || !json.success) {
									giverhubError({msg : 'Bad Response'});
								} else {
									var $activity = $this.closest('.activity');
									$activity.html('<button ' +
									'type="button" ' +
									'data-activity-post-id="'+post_id+'" ' +
									'data-loading-text="Undoing..." ' +
									'class="btn btn-sm btn-primary btn-undo-hide-activity-post">Undo Hide</button>');
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

			jQuery(document).on('click', '.btn-undo-hide-activity-post', function() {
				var $this = jQuery(this);
				try {
					$this.button('loading');
					var post_id = $this.data('activity-post-id');
					jQuery.ajax({
						url : '/members/undo_hide_activity_feed_post',
						dataType : 'json',
						type : 'post',
						data : { post_id : post_id },
						error : function() {
							giverhubError({msg : 'Request Failed.'});
						},
						complete : function() {
							$this.button('reset');
						},
						success : function(json) {
							try {
								if (!json || json === undefined || json.success === undefined || !json.success || json.html === undefined) {
									giverhubError({msg : 'Bad Response'});
								} else {
									var $tr = $this.closest('tr');

									var $html = jQuery(json.html);

									$html.find('.instant-donation-switch').each(function(i,e) {
										window.enableSwitch(e);
									});

									$tr.replaceWith($html);

									FB.XFBML.parse();
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

			jQuery(document).on('click', '.btn-delete-activity-post', function() {
				var $this = jQuery(this);
				try {
					if (!confirm('Are you sure that you want to remove this post?')) {
						return false;
					}
					$this.button('loading');
					var post_id = $this.data('activity-post-id');
					jQuery.ajax({
						url : '/members/delete_activity_feed_post',
						type : 'post',
						dataType : 'json',
						data : { post_id : post_id },
						error : function() {
							giverhubError({msg : 'Request Failed.'});
						},
						success : function(json) {
							try {
								if (!json || json === undefined || json.success === undefined || !json.success) {
									giverhubError({msg : 'Bad Response'});
								} else {
									var $activity = $this.closest('.activity');
									$activity.html('<button ' +
										'type="button" ' +
										'data-activity-post-id="'+post_id+'" ' +
										'data-loading-text="Undoing..." ' +
										'class="btn btn-sm btn-primary btn-undo-delete-activity-post">Undo Remove</button>');
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

			jQuery(document).on('click', '.btn-undo-delete-activity-post', function() {
				var $this = jQuery(this);
				try {
					$this.button('loading');
					var post_id = $this.data('activity-post-id');
					jQuery.ajax({
						url : '/members/undo_delete_activity_feed_post',
						dataType : 'json',
						type : 'post',
						data : { post_id : post_id },
						error : function() {
							giverhubError({msg : 'Request Failed.'});
						},
						complete : function() {
							$this.button('reset');
						},
						success : function(json) {
							try {
								if (!json || json === undefined || json.success === undefined || !json.success || json.html === undefined) {
									giverhubError({msg : 'Bad Response'});
								} else {
									var $tr = $this.closest('tr');

									var $html = jQuery(json.html);

									$html.find('.instant-donation-switch').each(function(i,e) {
										window.enableSwitch(e);
									});

									$tr.replaceWith($html);

									FB.XFBML.parse();
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

			var $make_post_comment_textarea = jQuery('.make-post-comment-textarea');

			$make_post_comment_textarea.each(function(i,e) {
				if (jQuery(e).is(':visible')) {
					jQuery(e).elastic();
				}
			});

			jQuery(document).on('input', '.make-post-comment-textarea', function() {
				try {
					var $this = jQuery(this);
					if (typeof(this.load_external_url_timer) !== "undefined") {
						clearInterval(this.load_external_url_timer);
						this.load_external_url_timer = undefined;
					}

					var text = $this.val();
					var pieces = text.split(/\s/);
					var youtube_video_ids = [];
					var urls = [];
					jQuery.each(pieces, function(i,piece) {
						var trimmed = piece.trim();
						var uri = window.parseUri(trimmed);
						if (uri && uri && uri.queryKey && uri.queryKey.v && trimmed.indexOf('youtube.com') != -1) {
							youtube_video_ids.push(uri.queryKey.v);
						} else if(uri && uri.protocol) {
							urls.push(trimmed);
						}
					});

					var url;
					if (urls.length) {
						url = urls[0];
					}

					if (youtube_video_ids.length) {
						var $yt_preview_container = $this.parent().find('.youtube-preview-container');

						jQuery.each(youtube_video_ids, function(i,video_id) {
							var container_id = 'youtube_preview_' + video_id;
							var $exists = $yt_preview_container.find('.' + container_id);
							if (!$exists.length) {
								$yt_preview_container.append(
									jQuery(
										'<div ' +
											'class="youtube-preview-div '+container_id+'" ' +
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
											'<button type="button" class="btn btn-danger btn-xs btn-delete-youtube-vid">x</button>' +
											'</div>'
									)
								);

								$yt_preview_container.find('.' + container_id).on('click', '.btn-delete-youtube-vid', function() {
									try {
										var $container = jQuery(this).parent();
										$container.addClass('hide');
									} catch(e) {
										giverhubError({e:e});
									}
								});
							}
						});


					}
					var $external_url_preview_container = $this.parent().find('.external-url-preview-container');
					if (typeof(url) === "string" && url.length && $external_url_preview_container.data('url') != url) {
						this.load_external_url_timer = setTimeout(function() {
							try {

								$external_url_preview_container.html('<img src="/images/ajax-loaders/ajax-loader2.gif">').removeClass('hide').data('external-url-id', null);
								jQuery.ajax({
									url : '/post/external_url',
									type : 'post',
									dataType : 'json',
									data : {
										url : url
									},
									error : function() {
										giverhubError({msg : 'Request Failed'});
									},
									success : function(json) {
										try {
											if (typeof(json) !== "object" || typeof(json.success) !== "boolean" || !json.success || typeof(json.url) !== "string" || typeof(json.url_id) === "undefined") {
												$external_url_preview_container.html('').addClass('hide').data('external-url-id', null);
												if (typeof(json.invalid_url) === "boolean" && json.invalid_url) {
													$external_url_preview_container
														.data('url', json.url)
														.data('external-url-id', null);
												} else {
													giverhubError({msg : 'Bad response.'});
												}
											} else {
												$external_url_preview_container
													.data('url', json.url)
													.data('external-url-id', json.url_id)
													.html(
														(json.image_url ? '<img class="image '+(json.image_size === "large" ? "large" : '')+'" src="'+json.image_url+'">' : '') +
															'<div class="content-wrapper">' +
															'<p class="title">'+json.title+'</p>' +
															'<p class="desc">'+json.description+'</p>' +
															'<p class="url">'+json.url+'</p>' +
															'</div>' +
															'<a href="#" class="delete-external-url">x</a>'
													);
											}
										} catch(e) {
											$external_url_preview_container.html('').addClass('hide').data('external-url-id', null);
											giverhubError({e:e});
										}
									}
								});
							} catch(e) {
								$external_url_preview_container.html('').addClass('hide').data('external-url-id', null);
								giverhubError({e:e});
							}
						},0);
					}
				} catch(e) {
					giverhubError({e:e});
				}
			});


			jQuery(document).on('keydown', '.make-post-comment-textarea', function(event) {
				try {
					var $this = jQuery(this);
					if ($this.hasClass('angular')) {
						return true;
					}
					var entity_id = $this.data('entity-id');
					var entity = $this.data('entity');
					var context = $this.data('context');
					var $top_wrapper = $this.closest('.activity-like-share-comment-wrapper');

					if (event.keyCode == KEY_ENTER) {
						if (event.shiftKey) {
							return true;
						} else {
							var text = $this.val();
							if (text.length) {
								$this.attr('disabled', 'disabled');
								var $external_url_preview_container = $this.parent().find('.external-url-preview-container');
								var $yt_preview_container = $this.parent().find('.youtube-preview-container');
								var $youtube_videos = $this.parent().find('.youtube-preview-div');
								var youtube_video_ids = [];
								$youtube_videos.each(function(i,e) {
									var $video = jQuery(e);
									if (!$video.hasClass('hide')) {
										youtube_video_ids.push($video.data('youtube-video-id'));
									}
								});

								jQuery.ajax({
									url : '/members/activity_feed_post_comment',
									type : 'post',
									dataType : 'json',
									data : {
										text : text,
										entity_id : entity_id,
										entity : entity,
										context : context,
										external_url_id : $external_url_preview_container.data('external-url-id'),
										youtube_video_ids : youtube_video_ids.length ? youtube_video_ids : null
									},
									error : function() {
										giverhubError({msg : 'Request Failed!'});
									},
									success : function(json) {
										try {
											if (json === undefined || !json || json.success === undefined || !json.success || json.html === undefined || typeof(json.comment_share_like_indicators) !== "string") {
												giverhubError({msg : 'Bad response.'});
											} else {
												$top_wrapper.find('.comment-share-like-indicators').replaceWith(json.comment_share_like_indicators);
												$this.val('');
												$external_url_preview_container.addClass('hide').html('').data('external-url-id', null);
												$yt_preview_container.html('');
												var $container = jQuery('#activity_feed_post_comments_'+entity+entity_id);
												var reveal = $container.hasClass('reveal');
												$container.replaceWith(json.html);
												if (reveal) {
													$container.addClass('reveal');
												}
											}
										} catch(e) {
											giverhubError({e:e});
										}
									},
									complete : function() {
										$this.removeAttr('disabled');
										$this.focus();
									}
								});
							}
							return false;
						}
					}
				} catch(e) {
					giverhubError({e:e});
				}
			});


			jQuery(document).on('click', '.display-more-comments', function() {
				try {
					var $this = jQuery(this);
					var $container = $this.closest('.activity-feed-comments-container');
					$container.addClass('reveal');
				} catch(e) {
					giverhubError({e:e});
				}
				return false;
			});

			jQuery(document).on('click', '.delete-comment', function() {
				try {
					if (!confirm('Are you sure that you want to delete this comment?')) {
						return false;
					}
					var $this = jQuery(this);
					var comment_id = $this.data('comment-id');
					var $comment_container = $this.closest('.comment-container');

					var $top_wrapper = $this.closest('.activity-like-share-comment-wrapper');

					$this.addClass('hide');
					var $deleting_indicator = $comment_container.find('.deleting-comment-indicator');

					$deleting_indicator.removeClass('hide');
					jQuery.ajax({
						url : '/members/delete_activity_feed_post_comment',
						type : 'post',
						dataType : 'json',
						data : {
							comment_id : comment_id
						},
						error : function() {
							$deleting_indicator.addClass('hide');
							$this.removeClass('hide');
							giverhubError({msg : 'Request Failed.'});
						},
						success : function(json) {
							try {
								if (typeof(json) !== "object" || typeof(json.success) !== "boolean" || !json.success || typeof(json.comment_share_like_indicators) !== "string") {
									giverhubError({msg : 'Bad Response!'});
								} else {
									$comment_container.remove();
									$top_wrapper.find('.comment-share-like-indicators').replaceWith(json.comment_share_like_indicators);
								}
							} catch(e) {
								$deleting_indicator.addClass('hide');
								$this.removeClass('hide');
								giverhubError({e:e});
							}
						}
					});
				} catch(e) {
					giverhubError({e:e});
				}
				return false;
			});

			jQuery(document).on('click', '.btn-activity-show-comments', function() {
				try {
					if (!body.data('signed-in')) {
						jQuery('#signin-or-join-first-modal').modal('show');
						return false;
					}

					var $this = jQuery(this);
					var $activity_comments_wrapper = $this.parent().parent().find('.activity-comments-wrapper');
					if ($activity_comments_wrapper.hasClass('hide')) {
						$activity_comments_wrapper.removeClass('hide');
						$activity_comments_wrapper.find('textarea').focus().elastic();
					} else {
						$activity_comments_wrapper.find('textarea').focus();
					}

				} catch(e) {
					giverhubError({e:e});
				}
				return false;
			});

			jQuery(document).on('click', '.btn-activity-like', function() {
				try {
					if (!body.data('signed-in')) {
						jQuery('#signin-or-join-first-modal').modal('show');
						return false;
					}

					var $this = jQuery(this);

					var like;
					if ($this.html() == 'Like') {
						like = true;
					} else if($this.html() == 'Unlike') {
						like = false;
					} else {
						throw "like button needs to have html = Like or Unlike";
					}

					var entity = $this.data('entity');
					var entity_id = $this.data('entity-id');


					var entity_url = $this.data('entity-url');
					if (typeof(entity_url) === 'string') {
						FB.api(
							"/?id="+encodeURIComponent(entity_url),
							function (response) {
								if (typeof(response) === "object" &&
									typeof(response.og_object) === "object" &&
									typeof(response.og_object.id) === "string") {
									FB.api('/me/permissions', function (response) {
										//console.log(response);
									});
									FB.api(
										"/"+response.og_object.id+"/likes?url="+encodeURIComponent(entity_url),
										like?"POST":"DELETE",
										function (response2) {
											//console.dir(like);
											//console.dir(response2);
										}
									);
								}
							}
						);
					}

					$this.html(like ? 'Unlike' : 'Like');

					jQuery.ajax({
						url : '/members/activity_like',
						data : {
							entity_id : entity_id,
							entity : entity,
							like : (like ? '1' : '0')
						},
						type : 'post',
						dataType : 'json',
						error : function() {
							giverhubError({msg : 'Invalid request.'});
						},
						success : function(json) {
							try {
								if (typeof(json) !== "object" || typeof(json.success) !== "boolean" || !json.success || typeof(json.comment_share_like_indicators) !== "string") {
									giverhubError({msg : 'Bad response'});
								} else {
									$this.parent().find('.comment-share-like-indicators').replaceWith(json.comment_share_like_indicators);
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

			jQuery(document).on('click', '.btn-post-comment-open', function() {
				try {
					var $this = jQuery(this);
					var $activity = jQuery(this).closest('.activity');
					$activity.find('.you-posted-a-comment').addClass('hide');
					$activity.removeClass('activity-is-from-me-to-other-user');
					$activity.find('.hidden-comment-to-other').each(function(i,e) {
						jQuery(e).removeClass('hidden-comment-to-other');
					});
				} catch(e) {
					giverhubError({e:e});
				}
				return false;
			});

			var $activity_table_tbody = jQuery('.activity-table-tbody');
			if ($activity_table_tbody.length) {
				function refresh_feed() {
					var $tr = $activity_table_tbody.find('tr').first();
					var latest_activity_id = $tr.data('activity-id');
					var user_id = $activity_table_tbody.data('user-id');
					var context = $activity_table_tbody.data('context');
					jQuery.ajax({
						url : '/members/refresh_feed',
						type : 'get',
						dataType : 'json',
						data : {
							latest_activity_id : latest_activity_id,
							user_id : user_id,
							context : context
						},
						error : function() {
							giverhubError({msg : 'Request failed.'});
						},
						complete : function() {
							setTimeout(refresh_feed, 10000);
						},
						success : function(json) {
							try {
								checkSuccess(json);

								if (typeof(json.html) === "string" && json.html.length) {
									$activity_table_tbody.prepend(json.html);
								}
							} catch(e) {
								giverhubError({e:e});
							}
						}
					});
				}
				refresh_feed();
			}
		} catch(e) {
			giverhubError({e:e});
		}
	});
} catch(e) {
	giverhubError({e:e});
}