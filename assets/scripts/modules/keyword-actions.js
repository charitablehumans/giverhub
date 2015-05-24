jQuery(document).ready(function () {
	try {
		$("#add-keyword-modal").keypress(function(e){
			if(e.which == 13) $(".btn-add-keyword-save").click();
		});
	} catch (e) {
		giverhubError({e : e});
	}
});