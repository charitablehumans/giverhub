try {
	jQuery(document).ready(function() {
		try {
			var $charity_admin_modal = jQuery('#charity-admin-modal');
			var $charity_admin_modal_facebook_page = jQuery('#charity-admin-modal-facebook-page');
			var $mission_textarea = jQuery('#charity-admin-data-mission-textarea');

			jQuery(document).on('click', '.btn-edit-charity-admin-data', function () {
				try {
					$charity_admin_modal.modal('show');

					if (!$charity_admin_modal.find('iframe').length) {
						$mission_textarea.wysihtml5({stylesheets: false});
					}
				} catch(e) {
					giverhubError({e:e});
				}
				return false;
			});

			jQuery(document).on('click', '.btn-edit-charity-admin-facebook-page', function () {
				try {
					$charity_admin_modal_facebook_page.modal('show');

				} catch(e) {
					giverhubError({e:e});
				}
				return false;
			});



			jQuery(document).on('click', '.btn-save-charity-admin-data', function() {
				var $this = jQuery(this);
				try {
					$this.button('loading');
					var $charity_admin_data_form = $this.closest('.modal').find('form');

					jQuery.ajax({
						url : '/charity_admin/save',
						data : $charity_admin_data_form.serialize(),
						dataType : 'json',
						type : 'post',
						error : function() {
							giverhubError({msg : 'Request Failed.'});
							$this.button('reset');
						},
						success : function(json) {
							if (!json || json === undefined || json.success === undefined || !json.success) {
								giverhubError({msg : 'Bad response from server.'});
								$this.button('reset');
							} else {
								window.location.reload();
							}
						}
					});
				} catch(e) {
					$this.button('reset');
					giverhubError({e:e});
				}
			});
		} catch(e) {
			giverhubError({e:e});
		}
	});
} catch(e) {
	giverhubError({e:e});
}