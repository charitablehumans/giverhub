try {
	jQuery(document).ready(function() {
		try {
			var $nonprofit_admin_edit_name_url_modal = jQuery('#nonprofit-admin-edit-name-url-modal');

			$nonprofit_admin_edit_name_url_modal.nameError = function(msg) {
				$nonprofit_admin_edit_name_url_modal.find('.name-error').html(msg).removeClass('hide');
			};

			$nonprofit_admin_edit_name_url_modal.urlError = function(msg) {
				$nonprofit_admin_edit_name_url_modal.find('.url-error').html(msg).removeClass('hide');
			};

			$nonprofit_admin_edit_name_url_modal.clearErrors = function() {
				$nonprofit_admin_edit_name_url_modal.find('.alert').addClass('hide');
			};

			jQuery(document).on('click', '.btn-edit-nonprofit-name', function() {
				try {
					$nonprofit_admin_edit_name_url_modal.clearErrors();
					$nonprofit_admin_edit_name_url_modal.modal('show');
				} catch(e) {
					giverhubError({e:e});
				}
			});

			$nonprofit_admin_edit_name_url_modal.on('click', '.btn-cancel', function() {
				try {
					$nonprofit_admin_edit_name_url_modal.find('form')[0].reset();
					$nonprofit_admin_edit_name_url_modal.modal('hide');
				} catch(e) {
					giverhubError({e:e});
				}
			});

			$nonprofit_admin_edit_name_url_modal.on('click', '.btn-reset', function() {
				try {
					$nonprofit_admin_edit_name_url_modal.clearErrors();
					$nonprofit_admin_edit_name_url_modal.find('form')[0].reset();
				} catch(e) {
					giverhubError({e:e});
				}
			});

			$nonprofit_admin_edit_name_url_modal.on('click','.btn-save', function() {
				var $this = jQuery(this);
				try {
					$nonprofit_admin_edit_name_url_modal.clearErrors();

					var charity_id = $this.data('charity-id');
					var name = $nonprofit_admin_edit_name_url_modal.find('input.name').val();
					var url = $nonprofit_admin_edit_name_url_modal.find('input.url').val();

					var errors = false;
					if (name.length < 3) {
						$nonprofit_admin_edit_name_url_modal.nameError('Name is too short. minimum 3 letters.');
						errors = true;
					}
					if (url.length < 3) {
						$nonprofit_admin_edit_name_url_modal.urlError('Url is too short. minimum 3 letters.');
						errors = true;
					}

					if (errors) {
						return true;
					}

					$this.button('loading');

					jQuery.ajax({
						url : '/charity_admin/save_name_url',
						type : 'post',
						dataType : 'json',
						data : {
							charity_id : charity_id,
							name : name,
							url : url
						},
						error : function() {
							giverhubError({msg : 'Request Failed'});
						},
						success : function(json) {
							try {
								if (typeof(json) !== 'object' || typeof(json.success) != 'boolean' || !json.success || typeof(json.url) !== 'string') {
									var has_error_message = false;
									if (typeof(json.name_error) === 'string') {
										$nonprofit_admin_edit_name_url_modal.nameError(json.name_error);
										has_error_message = true;
									}
									if (typeof(json.url_error) === 'string') {
										$nonprofit_admin_edit_name_url_modal.urlError(json.url_error);
										has_error_message = true;
									}

									if (!has_error_message) {
										giverhubError({msg : 'Bad response from server.'});
									}
								} else {
									window.location = json.url;
									giverhubSuccess({subject : 'Saved!', msg : 'Please wait while page is reloading.'});
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


			var $upload_charity_header_pic = jQuery('#upload-charity-header-pic');

			var $upload_charity_header_pic_button = $upload_charity_header_pic.find('button');
			var $upload_charity_header_pic_button_span = $upload_charity_header_pic_button.find('span');
			$upload_charity_header_pic
				.fileupload()
				.bind(
					'fileuploaddone',
					function(a,b) {
						try {
							var d = jQuery.parseJSON(b.result);

							switch(d[0].error) {
								case undefined:
									jQuery('.nonprofit-secondary-header').css('background-image', 'url('+d[0].url+')');
									$upload_charity_header_pic_button_span.html('Change Cover');
									jQuery('.btn-delete-charity-header-pic').button('reset').removeClass('hide');
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
						$upload_charity_header_pic_button_span.html('0%');
					}
				)
				.bind(
					'fileuploadfail',
					function(a,b) {
						giverhubError({msg : 'Something went wrong when uploading the file."' + b.errorThrown + '". Notice that files bigger than 10Mb should not be uploaded. Thank you for your patience.'});
					}
				)
				.bind('fileuploadprogressall', function (e, data) {
					var progress = parseInt(data.loaded / data.total * 100, 10);

					$upload_charity_header_pic_button_span.html(progress+'%');
				});

			jQuery(document).on('click', '.btn-delete-charity-header-pic', function() {
				var $this = jQuery(this);
				try {
					$this.button('loading');

					jQuery.ajax({
						url : '/upload/delete_charity_header_pic',
						type : 'post',
						dataType : 'json',
						data : { charity_id : $this.data('charity-id') },
						error : function() {
							giverhubError({msg : 'Request failed.'});
							$this.button('reset');
						},
						success : function(json) {
							try {
								if (typeof(json) !== "object" || typeof(json.success) !== "boolean" || !json.success) {
									giverhubError({msg : 'Bad response.'});
								} else {
									jQuery('.nonprofit-secondary-header').css('background', 'none');
									$this.addClass('hide');
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

			var $upload_logo_wrapper = jQuery('.upload-logo-wrapper');
			var $nonprofit_name_wrapper = jQuery('.nonprofit-name-wrapper');
			var $nonprofit_logo_img = jQuery('.nonprofit-logo-img');
			var $your_logo_here = jQuery('.your-logo-here');
			jQuery(document).on('click', '.btn-start-upload-nonprofit-logo', function() {
				try {
					$upload_logo_wrapper.addClass('upload-logo-wrapper-hidden');
					$upload_logo_wrapper.removeClass('hide');
					setTimeout(function() {$upload_logo_wrapper.removeClass('upload-logo-wrapper-hidden');},10);

					$nonprofit_name_wrapper
						.addClass('col-md-7')
						.addClass('col-sm-6')
						.removeClass('col-sm-8')
						.removeClass('col-md-9');
				} catch(e) {
					giverhubError({e:e});
				}
			});

			var $upload_charity_logo = jQuery('#upload-charity-logo');

			var $upload_charity_logo_button = $upload_charity_logo.find('button');
			var $upload_charity_logo_button_span = $upload_charity_logo_button.find('span');

			$upload_charity_logo
				.fileupload()
				.bind(
					'fileuploaddone',
					function(a,b) {
						try {
							var d = jQuery.parseJSON(b.result);

							switch(d[0].error) {
								case undefined:
									$nonprofit_logo_img.attr('src', d[0].url).removeClass('hide');
									$upload_charity_logo_button_span.html('Change Logo');
									$your_logo_here.addClass('hide');
									jQuery('.btn-delete-charity-logo').button('reset').removeClass('hide');
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
						$upload_charity_logo_button_span.html('0%');
					}
				)
				.bind(
					'fileuploadfail',
					function(a,b) {
						giverhubError({msg : 'Something went wrong when uploading the file."' + b.errorThrown + '". Notice that files bigger than 10Mb should not be uploaded. Thank you for your patience.'});
					}
				)
				.bind('fileuploadprogressall', function (e, data) {
					var progress = parseInt(data.loaded / data.total * 100, 10);

					$upload_charity_logo_button_span.html(progress+'%');
				});

			jQuery(document).on('click', '.btn-delete-charity-logo', function() {
				var $this = jQuery(this);
				try {
					$this.button('loading');

					jQuery.ajax({
						url : '/upload/delete_charity_logo',
						type : 'post',
						dataType : 'json',
						data : { charity_id : $this.data('charity-id') },
						error : function() {
							giverhubError({msg : 'Request failed.'});
							$this.button('reset');
						},
						success : function(json) {
							try {
								if (typeof(json) !== "object" || typeof(json.success) !== "boolean" || !json.success) {
									giverhubError({msg : 'Bad response.'});
								} else {
									$nonprofit_logo_img.addClass('hide');
									$your_logo_here.removeClass('hide');
									$this.addClass('hide');
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

		} catch(e) {
			giverhubError({e:e});
		}
	});
} catch(e) {
	giverhubError({e:e});
}