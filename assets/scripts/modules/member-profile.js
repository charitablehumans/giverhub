jQuery(document).ready(function() {
	try {

		var $trending_petitions_carousel = jQuery('#trending-petitions-carousel');
		function ellipsisActiveTrendingPetitions(what) {
			$trending_petitions_carousel.find(what).find('.ellipsis').each(function(i,e) {
				jQuery(e).removeClass('ellipsis');
				jQuery(e).dotdotdot();
			});
		}
		$trending_petitions_carousel.on('slid.bs.carousel', function () {
			ellipsisActiveTrendingPetitions('.active'); // run during every slide
		});

		ellipsisActiveTrendingPetitions('.active'); // run for the first slide

		var $search_trending_petitions = jQuery('.search-trending-petitions');

		var $trending_petitions_search_results = jQuery('#trending-petitions-search-results');
		var $trending_petitions_search_loading = jQuery('#trending-petitions-search-loading');


		if ($search_trending_petitions.length) {

			function loading() {
				$trending_petitions_carousel.addClass('hide');
				$trending_petitions_search_results.addClass('hide');
				$trending_petitions_search_loading.removeClass('hide');
			}

			var cached_trending_search_results = {};
			$search_trending_petitions.on('keyup', function() {
				try {
					var $this = jQuery(this);
					var search_text = $this.val();

					if (!search_text.length) {
						$trending_petitions_search_results.addClass('hide');
						$trending_petitions_search_loading.addClass('hide');
						$trending_petitions_carousel.removeClass('hide');
						return true;
					}

					var result = cached_trending_search_results[search_text];

					if (typeof(result) === "undefined") { // we do not have it in cache
						loading();

						// remember that we are loading the search_text
						cached_trending_search_results[search_text] = {loading : true};

						// make the ajax request.. duh
						jQuery.ajax({
							url : '/home/trending_petitions_search',
							type : 'get',
							data : { search_text : search_text },
							dataType : 'json',
							error : function() {
								// this is annoying when a user clicks a link during this ajax request causing this request to fail.
								//giverhubError({msg : 'Request Failed!'});
							},
							success : function(json) {
								try {
									if (typeof(json) !== "object" || typeof(json.html) !== "string" || typeof(json.search_text) !== "string") {
										giverhubError({msg : 'Bad response!'});
									} else {
										if (cached_trending_search_results[json.search_text] === undefined) {
											return; // something is wrong
										}
										if (!cached_trending_search_results[json.search_text]['loading']) {
											return; // something is wrong
										}

										// store the rendered template in result cache
										cached_trending_search_results[json.search_text]['results'] = json.html;

										// loading is complete
										cached_trending_search_results[json.search_text]['loading'] = false;

										// if the value has not changed we want to display the results.
										// value can change after we started the ajax request.. ajax is not syncronous, remember!
										if ($this.val() == json.search_text) {
											$trending_petitions_search_loading.addClass('hide');
											$trending_petitions_carousel.addClass('hide');
											$trending_petitions_search_results.html(json.html);
											$trending_petitions_search_results.removeClass('hide');
											$trending_petitions_search_results.find('.ellipsis').each(function(i,e) {
												jQuery(e).removeClass('ellipsis');
												jQuery(e).dotdotdot();
											});
										}
									}
								} catch(e) {
									giverhubError({e:e});
								}
							}
						});
					} else {
						if (result['loading']) {
							// this search_text is still loading.. so we display the loading template
							loading();
						} else {
							// we have the result in cache .. display!
							$trending_petitions_search_loading.addClass('hide');
							$trending_petitions_carousel.addClass('hide');
							$trending_petitions_search_results.html(result['results']);
							$trending_petitions_search_results.removeClass('hide');
							$trending_petitions_search_results.find('.ellipsis').each(function(i,e) {
								jQuery(e).removeClass('ellipsis');
								jQuery(e).dotdotdot();
							});
						}
					}
				} catch(e) {
					giverhubError({e:e});
				}
			});
		}

		jQuery(document).on('click', '.change-profile-pic-text', function() {
			try {
				var $this = jQuery(this);
				var $form = $this.closest('form');
				$input = $form.find('input');
				$input.trigger('click');
			} catch(e) {
				giverhubError({e:e});
			}
		});
		function refreshCauses() {
			try {
				var $cont = jQuery('#selected-categories-container');
				if (!$cont.length) {
					return;
				}

				var $lis = $cont.find('li');

				var count = 0;
				$lis.each(function(i,li) {
					var $li = jQuery(li);

					if (!$li.hasClass('show-more-causes') && !$li.hasClass('your-causes')) {
						count++;
						if (count > $cont.data('cause-limit')) {
							$li.addClass('hide');
						} else {
							$li.removeClass('hide');
						}
					}
				});

			} catch(e) {
				giverhubError({e:e});
			}
		}
		refreshCauses();

		jQuery(document).on('click', '.btn-add-categories-category', function() {
			return false;
		});

		jQuery(document).on('click', '.btn-add-categories-cause', function() {
			var active = jQuery(this).hasClass('active');
			var btnGroup = jQuery(this).closest('.btn-group');
			var parentBtn = btnGroup.find('.btn-add-categories-category');
			if (active) {
				parentBtn.addClass('active');
			} else {
				var allInactive = true;
				jQuery(this).parent().parent().find('.btn-add-categories-cause').each(function(i, e) {
					if (jQuery(e).hasClass('active')) {
						allInactive = false;
					}
				});
				if (allInactive) {
					parentBtn.removeClass('active');
				}
			}
			return false;
		});

		jQuery(document).on('click', '.add-categories-btn', function() {
			jQuery('#add-categories-modal').modal('show');
		});

		jQuery(document).on('click', '.btn-add-categories-save', function() {
			var btn = jQuery(this);
			try {
				btn.button('loading');

				var addCategoriesModal = jQuery('#add-categories-modal');
				var categoryToggles = addCategoriesModal.find('.btn-add-categories-category');
				var categories = [];
				jQuery.each(categoryToggles, function(i,e) {
					var $e = jQuery(e);
					if ($e.hasClass('active')) {
						categories.push($e.data('charity-category-id'));
					}
				});
				categories = categories.join(',');

				var causeToggles = addCategoriesModal.find('.btn-add-categories-cause');
				var causes = [];
				jQuery.each(causeToggles, function(i,e) {
					var $e = jQuery(e);
					if ($e.hasClass('active')) {
						causes.push($e.data('charity-cause-id'));
					}
				});
				causes = causes.join(',');

				jQuery.ajax({
					url : '/members/save_categories_and_causes',
					dataType : 'json',
					type : 'post',
					data : { categories : categories, causes : causes },
					error : function() {
						giverhubError({msg : 'Request failed.'});
					},
					complete : function() {
						btn.button('reset');
					},
					success : function(json) {
						try {
							if (!json || json === undefined || json.success === undefined || !json.success || json.causesHtml === undefined) {
								giverhubError({msg : 'Bad response from server.'});
							} else {
								jQuery('#causes-wrapper').html(json.causesHtml);
								refreshCauses();
								jQuery('#add-categories-modal').modal('hide');
							}
						} catch(e) {
							giverhubError({e:e});
						}
					}
				});
			} catch(e) {
				btn.button('reset');
				giverhubError({e:e});
			}
		});

		jQuery(document).on('click', '.btn-remove-user-category', function() {
			try {
				var btn = jQuery(this);
				var charityCategoryId = btn.data('charity-category-id');
				var userCategoryId = btn.data('user-category-id');

				jQuery.ajax({
					url : '/members/remove_category',
					type : 'post',
					dataType : 'json',
					data : { userCategoryId : userCategoryId },
					error : function() {
						giverhubError({msg : 'Request Failed.'});
					},
					success : function(json) {
						try {
							if (!json || json === undefined || json.success === undefined) {
								giverhubError({msg : 'Bad response from server.'});
							} else {
								var parent = btn.parent();
								parent.addClass('deleting');
								parent.toggle('scale', function () {
								 	jQuery(this).detach();
									refreshCauses();
								});

								jQuery('.btn-add-categories-category[data-charity-category-id="'+charityCategoryId+'"]').removeClass('active');
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

		jQuery(document).on('click', '.btn-remove-user-cause', function() {
			try {
				var btn = jQuery(this);
				var charityCauseId = btn.data('charity-cause-id');
				var userCauseId = btn.data('user-cause-id');

				jQuery.ajax({
					url : '/members/remove_cause',
					type : 'post',
					dataType : 'json',
					data : { userCauseId : userCauseId },
					error : function() {
						giverhubError({msg : 'Request Failed.'});
					},
					success : function(json) {
						try {
							if (!json || json === undefined || json.success === undefined) {
								giverhubError({msg : 'Bad response from server.'});
							} else {
								btn.parent().toggle('scale', function () {
									jQuery(this).detach();
									refreshCauses();
								});
								jQuery('.btn-add-categories-cause[data-charity-cause-id="'+charityCauseId+'"]').removeClass('active');
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

		function fileUploadError(msg) {
			giverhubError({msg : msg });
		}

		jQuery('#upload-profile-photo-form')
			.fileupload()
			.bind(
				'fileuploaddone',
				function(a,b) {
					try {
						var d = jQuery.parseJSON(b.result);

						switch(d[0].error) {
							case undefined:
								var $user_avatar_progress = jQuery('.user-avatar-progress');
								var $user_avatar_progress_span = $user_avatar_progress.find('span');
								$user_avatar_progress_span.html('100%');
								$user_avatar_progress.addClass('hide');

								jQuery('.user_avatar').attr('src', d[0].url).removeClass('hide');
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
					var $user_avatar_progress = jQuery('.user-avatar-progress');
					var $user_avatar_progress_span = $user_avatar_progress.find('span');
					$user_avatar_progress_span.html('0%');
					jQuery('.user_avatar').addClass('hide');
					$user_avatar_progress.removeClass('hide');
				}
			)
			.bind(
				'fileuploadfail',
				function(a,b) {
					fileUploadError('Something went wrong when uploading the file."' + b.errorThrown + '". Notice that files bigger than 10Mb should not be uploaded. Thank you for your patience.');
				}
			)
			.bind('fileuploadprogressall', function (e, data) {
				var progress = parseInt(data.loaded / data.total * 100, 10);

				var $user_avatar_progress = jQuery('.user-avatar-progress');
				var $user_avatar_progress_span = $user_avatar_progress.find('span');
				$user_avatar_progress_span.html(progress+'%');
			});


		var $upload_charity_photos_form = jQuery('#upload-charity-photos-form');

		if ($upload_charity_photos_form.length) {
			var $upload_charity_photos_form_footer_block_btn = $upload_charity_photos_form.find('.footer_block_btn');
			$upload_charity_photos_form.on('mouseover', 'input', function () {
				$upload_charity_photos_form_footer_block_btn.addClass('hover');
			});

			$upload_charity_photos_form.on('mouseout', 'input', function () {
				$upload_charity_photos_form_footer_block_btn.removeClass('hover');
			});
		}

		var $btn_follow_user = jQuery('.btn-member-dashboard-follow-user');

		if ($btn_follow_user.length) {
			jQuery(document).on('click', '.btn-member-dashboard-follow-user', function() {
				var $this = jQuery(this);
				try {
					$this.button('loading');
					var user_id = $this.data('user-id');
					jQuery.ajax({
						url : '/members/toggleFollowUser',
						type : 'post',
						dataType : 'json',
						data : { userId : user_id },
						error : function() {
							giverhubError({msg : 'Request Failed.'});
						},
						success : function(json) {
							try {
								if (typeof(json) !== "object" || typeof(json.success) !== "boolean" || !json.success || typeof(json.following) !== "boolean") {
									giverhubError({msg : 'Bad response.'});
								} else {
									$btn_follow_user.addClass('hide');

									$btn_follow_user.each(function(i,btn) {
										var $btn = jQuery(btn);
										if ($btn.hasClass('follow') && !json.following) {
											$btn.removeClass('hide');
										}
										if ($btn.hasClass('unfollow') && json.following) {
											$btn.removeClass('hide');
										}
									});
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
		}

		var $givercoin_value_update = jQuery('.givercoin-value-update');
		if ($givercoin_value_update.length) {
			setInterval(function() {
				jQuery.ajax({
					url : '/members/get_givercoin',
					type : 'get',
					dataType : 'html',
					data : { user_id : $givercoin_value_update.data('user-id') },
					success : function(html) {
						$givercoin_value_update.html(html);
					}
				});
			}, 10000);
		}
	} catch(e) {
		giverhubError({e:e});
	}

});
