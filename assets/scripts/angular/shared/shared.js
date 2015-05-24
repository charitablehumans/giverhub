window.unsafeFilter = ['$sce', function ($sce) {
	return function (val) {
		return $sce.trustAsHtml(val);
	};
}];

window.nl2brFilter = ['$sce', function($sce){
	return function(text){
		return $sce.trustAsHtml(text?text.replace(/\n/g, '<br/>'):'');
	};
}];

window.userService = function() {
	var $body = jQuery('body');

	var signedIn = !!$body.data('signed-in');

	var user;
	if (signedIn) {
		user = $body.data('signed-in-user');
	}

	return {
		signedIn : signedIn,
		user : user
	};
};

