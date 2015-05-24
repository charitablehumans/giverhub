try {
	jQuery(document).ready(function() {
		try {

			if (!body.hasClass('page-index')) {
				return;
			}

			jQuery('#upload-page-logo-form')
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

			var $edit_page_desc_modal = jQuery('#edit-page-desc-modal');

			if ($edit_page_desc_modal.length) {

				var $edit_page_desc_textarea = $edit_page_desc_modal.find('textarea');
				$edit_page_desc_textarea.summernote({
					toolbar : [
						['style', ['bold', 'italic', 'underline', 'clear']]],
					styleWithSpan : false
				});
				$edit_page_desc_textarea.code($edit_page_desc_textarea.data('initial-value'));

				$edit_page_desc_modal.find('.note-editable').attr('placeholder', $edit_page_desc_textarea.attr('placeholder'));

				jQuery(document).on('click', '.btn-edit-page-desc', function () {
					try {
						$edit_page_desc_modal.modal('show');
						$edit_page_desc_modal.hide = false;
					} catch (e) {
						giverhubError({e : e});
					}
				});

				$edit_page_desc_modal.on('click', '.btn-save-page-description', function() {
					var $this = jQuery(this);
					try {
						$this.button('loading');

						var page_id = $this.data('page-id');
						var desc = $edit_page_desc_textarea.code().trim();

						jQuery.ajax({
							url : '/pageadmin/desc',
							type : 'post',
							dataType : 'json',
							data : { page_id : page_id, desc: desc},
							error : function() {
								giverhubError({msg : 'Request failed.'});
							},
							complete : function() {
								$this.button('reset');
							},
							success : function(json) {
								try {
									if (typeof(json) !== "object" || typeof(json.success) !== "boolean" || !json.success) {
										giverhubError({msg : 'Bad response.'});
									} else {
										jQuery('#description-wrapper').find('.description').html(desc);
										$edit_page_desc_modal.hide = true;
										$edit_page_desc_modal.modal('hide');
									}
								} catch(e) {
									giverhubError({e:e});
								}
							}
						});
					} catch(e) {
						giverhubError({e:e});
					}
				});

				$edit_page_desc_modal.on('hide.bs.modal', function () {
					if ($edit_page_desc_modal.hide) {
						return true;
					}
					return confirm('Are you sure that you want to close? You may lose all your changes.');
				});
			}

		} catch(e) {
			giverhubError({e:e});
		}
	});
} catch(e) {
	giverhubError({e : e});
}