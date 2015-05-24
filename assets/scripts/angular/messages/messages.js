var messagesApp = angular.module('messagesApp', ['ui.bootstrap', 'angularMoment', 'monospaced.elastic', 'perfect_scrollbar']);

messagesApp.service('userService', window.userService);

messagesApp.filter('nl2br', window.nl2brFilter);


messagesApp.service('messageService', ['$http', '$timeout', function($http, $timeout) {
	var $messages_controller = jQuery('.messages-controller');

	var messages = $messages_controller.data('messages');
	var is_charity = $messages_controller.data('charity');
	var charity_page_id;
	if (is_charity) {
		charity_page_id = is_charity.id;
	}
	var _signed_in_user;

	var getUsers = function(){
		return messages.users;
	};

	var getNonprofits = function(){
		return messages.nonprofits;
	};

	var getCharityPageId = function() {
		return charity_page_id;
	};

	var added_message_ids = {};

	var refresh_messages_scroll;

	var setRefreshMessagesScroll = function(hehe) {
		refresh_messages_scroll = hehe;
	};

	var getRefreshMessagesScroll = function() {
		return refresh_messages_scroll;
	};


	function guid() {
		function s4() {
			return Math.floor((1 + Math.random()) * 0x10000)
				.toString(16)
				.substring(1);
		}
		return s4() + s4() + '-' + s4() + '-' + s4() + '-' +
			s4() + '-' + s4() + s4() + s4();
	}

	var setUser = function(the_user) {
		_signed_in_user = the_user;
	};

	var send = function(user,msg,volunteer) {
		var tmp_id = guid();
		added_message_ids[tmp_id] = true;
		var message = {
			id: false,
			tmp_id : tmp_id,
			message : msg,
			from: _signed_in_user,
			to: user.entity,
			sent : new Date(),
			seen : null,
			unread_by_current_user : false
		};

		if (typeof(volunteer) === "object") {
			volunteer.status = 'accepted';
			message.volunteer = volunteer;
		}

		if (charity_page_id) {
			message.from = is_charity;
		}
		user.messages.push(message);
		user.last_message = message;

		var payload = {entity_id : user.entity.id, entity_type: user.entity.type, msg : msg, tmp_id : tmp_id};

		if (charity_page_id) {
			payload.from_charity_id = charity_page_id;
		}

		if (typeof(volunteer) === "object") {
			payload.volunteer_id = volunteer.id;
		}

		$http.post('/members/send_message', payload)
			.success(function(json) {
				if (typeof(json) !== "object" || typeof(json.success) !== 'boolean' || !json.success || typeof(json.message) !== 'object') {
					giverhubError({msg : 'Bad response from server.'});
				} else {
					message.id = json.message.id;
					added_message_ids[message.id] = true;
				}
			})
			.error(function() {
				giverhubError({msg : 'Request Failed'});
			})
			.finally(function() {
			});
	};

	var x = 0;
	var poll = function() {
		$timeout(function() {
			var url = '/members/new_messages/' + messages.last_message_id;
			if (charity_page_id) {
				url += '?charity_id='+charity_page_id;
			}
			$http.get(url).
				success(function(json) {
					if (typeof(json) === "object" &&
						typeof(json.success) === "boolean" &&
						json.success &&
						typeof(json.messages) === "object" &&
						json.messages.length) {
						// we got new messages

						var added_new = false;
						angular.forEach(json.messages, function(message) {
							if (!added_message_ids[message.id] && !added_message_ids[message.tmp_id]) {

								if (!charity_page_id && message.to.type == 'charity' || message.from.type == 'charity') {
									var charity_id;

									if (message.to.type == 'charity') {
										charity_id = message.to.id;
									} else {
										charity_id = message.from.id;
									}

									angular.forEach(messages.nonprofits, function(nonprofit) {
										if (nonprofit.entity.id == charity_id) {
											nonprofit.messages.push(message);
											nonprofit.last_message = message;
											messages.last_message_id = message.id;
											added_new = true;
										}
									});
								} else {
									var user_id;

									if (message.to.type == "user" && message.from.type == "user") {
										if (message.to.id == message.from.id) {
											user_id = message.to.id;
										} else {
											if (message.to.id == _signed_in_user.id) {
												user_id = message.from.id;
											} else {
												user_id = message.to.id;
											}
										}
									} else {
										if (message.to.type == "user") {
											user_id = message.to.id;
										} else {
											user_id = message.from.id;
										}
									}

									angular.forEach(messages.users, function (user) {
										if (user.entity.id == user_id) {
											user.messages.push(message);
											user.last_message = message;
											messages.last_message_id = message.id;
											added_new = true;
										}
									});
								}
								seen(open_user);
							}
						});

						if (added_new) {
							refresh_messages_scroll[Math.random()] = true;
						}
					}
				}).
				finally(function() {
					poll();
				});
		}, 1000);
	};
	poll();

	var open_user;

	var seen = function(user) {
		open_user = user;
		var temp_users;
		if (user.entity.type == 'charity') {
			temp_users = messages.nonprofits;
		} else if (user.entity.type == 'user') {
			temp_users = messages.users;
		}
		angular.forEach(temp_users, function(u) {
			if (u.entity.id == user.entity.id && u.messages.length) {
				var seen_ids = [];
				angular.forEach(u.messages, function(message) {
					if (!message.seen) {
						message.seen = new Date();
						seen_ids.push(message.id);
					}
				});
				u.last_message.seen = new Date();
				u.last_message.unread_by_current_user = false;
				if (seen_ids.length) {
					var payload = {seen_ids : seen_ids};
					if (charity_page_id) {
						payload.charity_id = charity_page_id;
					}
					$http.post('/members/seen_messages', payload)
						.error(function () {
							giverhubError({msg : 'Request Failed'});
						})
						.finally(function () {
						});
				}
			}
		});
	};

	return {
		getUsers: getUsers,
		getNonprofits : getNonprofits,
		getCharityPageId : getCharityPageId,
		send: send,
		setUser : setUser,
		setRefreshMessagesScroll : setRefreshMessagesScroll,
		getRefreshMessagesScroll : getRefreshMessagesScroll,
		poll : poll,
		seen : seen
	};
}]);


messagesApp.controller('MessagesController', ['$scope', '$modal', '$timeout', 'messageService', 'userService', function($scope, $modal, $timeout, messageService, userService) {
	$scope.users = messageService.getUsers();
	$scope.nonprofits = messageService.getNonprofits();

	$scope.msg = {msg : ''};
	$scope.enter_to_send = {enter_to_send : false};

	$scope.signed_in_user = userService.user;
	$scope.charity_page_id = messageService.getCharityPageId();


	$scope.refresh_messages_scroll = {};

	messageService.setUser(userService.user);
	messageService.setRefreshMessagesScroll($scope.refresh_messages_scroll);

	$scope.open = function(user) {
		$scope.user_open = user;
		messageService.seen(user);
		try {
			jQuery('.respond-wrapper').find('textarea')[0].focus();
		} catch(e) {}
		$scope.$broadcast('rebuild:me');
		$scope.refresh_messages_scroll[Math.random()] = true;
	};

	if ($scope.users.length) {
		$scope.open($scope.users[0]);
	} else {
		$scope.user_open = false;
	}

	$scope.send = function(user,msg) {
		messageService.send(user,msg);
		$scope.msg.msg = '';
		jQuery('.respond-wrapper').find('textarea')[0].focus();
		$scope.refresh_messages_scroll[Math.random()] = true;
	};

	$scope.keypress = function($event) {
		if ($scope.enter_to_send.enter_to_send && $event.charCode == 13 && !$event.shiftKey) {
			$scope.send($scope.user_open, $scope.msg.msg);
			$event.preventDefault();
		}
	};

	$scope.change_enter_to_send = function() {
		jQuery('.respond-wrapper').find('textarea')[0].focus();
	};

	$scope.accept_request = function(message) {
		$modal.open({
			templateUrl : 'accept_vol_request_modal_body.html',
			windowClass : 'gh-modal-style-2 accept_vol_request_modal modal fade in',
			controller : 'AcceptVolRequestModalCtrl',
			resolve: {
				message: function() {
					return angular.copy(message);
				},
				user_open : function() {
					return $scope.user_open;
				}
			}
		});
	};
}]);

messagesApp.controller('AcceptVolRequestModalCtrl', [
	'$scope', '$timeout', '$log', '$modal', '$http', '$modalInstance', 'messageService', 'message', 'user_open',
	function($scope, $timeout, $log, $modal, $http, $modalInstance, messageService, message, user_open) {
		$scope.message = message;
		$scope.msg = { msg : message.to.name + ' has accepted your volunteer request to participate in the following event.' };
		$scope.cancel = function () {
			$modalInstance.dismiss('cancel');
		};
		$modalInstance.opened.then(function() {
			$timeout(function() {
				$scope.event_height = angular.element('#event-wrapper-in-modal').height()+30+"px";
			}, 666);
		});

		$scope.send = function() {
			messageService.send(user_open,$scope.msg.msg, angular.copy(message.volunteer));
			var x = messageService.getRefreshMessagesScroll();
			x[Math.random()] = true;
			$modalInstance.dismiss('cancel');
		};
	}
]);


angular.element(document).ready(function() {
	var $messages_controller = jQuery('.messages-controller');

	$messages_controller.each(function(i,e) {
		angular.bootstrap(e,['messagesApp']);
	});
});
