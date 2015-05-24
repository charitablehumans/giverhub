var volunteeringOpportunitiesApp = angular.module('volunteeringOpportunitiesApp', ['ui.bootstrap']);

volunteeringOpportunitiesApp.filter('unsafe', window.unsafeFilter);

// update popover template for binding unsafe html
angular.module("template/popover/popover.html", []).run(["$templateCache", function ($templateCache) {
	$templateCache.put("template/popover/popover.html",
		"<div class=\"popover {{placement}}\" ng-class=\"{ in: isOpen(), fade: animation() }\">\n" +
			"  <div class=\"arrow\"></div>\n" +
			"\n" +
			"  <div class=\"popover-inner\">\n" +
			"      <h3 class=\"popover-title\" ng-bind-html=\"title | unsafe\" ng-show=\"title\"></h3>\n" +
			"      <div class=\"popover-content\"ng-bind-html=\"content | unsafe\"></div>\n" +
			"  </div>\n" +
			"</div>\n" +
			"");
}]);

volunteeringOpportunitiesApp.service('userService', window.userService);

volunteeringOpportunitiesApp.service('oppService', function() {
	var $volunteering_info = jQuery('.volunteering-info');
	var oppList = $volunteering_info.data('opportunities');
	if (typeof(window.scopes_for_apply) === "undefined") {
		window.scopes_for_apply = [];
	}

	var scopes_for_apply = window.scopes_for_apply;

	var apply_scopes = function() {
		angular.forEach(scopes_for_apply, function(scope) {
			if(!scope.$$phase) {
				scope.$apply();
			}
		});
	};

	var addOpportunity = function(newOpp) {
		oppList.push(newOpp);
		apply_scopes();
	};

	var updateOpportunity = function(oldOpp, newOpp) {
		angular.forEach(oppList,function(opp,key) {
			if (opp.id == oldOpp.id) {
				newOpp.$$hashKey = oldOpp.$$hashKey;
				oppList[key] = newOpp;
				return false;
			}
		});

		apply_scopes();
	};

	var getOpportunities = function(id){
		if (typeof(id) === "undefined") {
			return oppList;
		} else {
			var ret;
			angular.forEach(oppList, function(opp) {
				if (opp.id == id) {
					ret = opp;
					return false;
				}
			});
			return ret;
		}
	};

	/**
	 * Save any scopes that needs to be applied .. this is useful when running multiple angular apps on the same page .. otherwise updating one app will not update the other.
	 * @param scope
	 */
	var addScopeForApply = function(scope) {
		scopes_for_apply.push(scope);
	};

	var deleteOpportunity = function(opportunity) {
		angular.forEach(oppList,function(opp,key) {
			if (opp.id == opportunity.id) {
				oppList.splice(key, 1);
				return false;
			}
		});
		apply_scopes();
	};

	return {
		add: addOpportunity,
		get: getOpportunities,
		update: updateOpportunity,
		delete : deleteOpportunity,
		addScopeForApply : addScopeForApply
	};
});

volunteeringOpportunitiesApp.filter('nl2br', window.nl2brFilter);

volunteeringOpportunitiesApp.controller('VolunteeringOpportunitiesListController', ['$scope', 'oppService', function($scope, oppService) {
	oppService.addScopeForApply($scope);
	$scope.opportunities = oppService.get();
	$scope.order_by = '+next_timestamp';
}]);

volunteeringOpportunitiesApp.controller('VolunteeringOpportunityCommentsController', ['$scope', '$http', 'userService', 'oppService', function($scope, $http, userService, oppService) {
	$scope.user = userService.user;
	$scope.userIsSignedIn = userService.signedIn;

	$scope.showComments = false;

	$scope.limitComments = -4;
	$scope.view_more = true;
	$scope.max_safe_integer = Number.MAX_SAFE_INTEGER;

	$scope.like = function(opportunity, like) {
		if (!$scope.userIsSignedIn) {
			jQuery('#signin-or-join-first-modal').modal('show');
			return;
		}
		opportunity.hasSignedInUserLiked = !opportunity.hasSignedInUserLiked;

		$http.post('/volunteering_opportunity/like', {opportunity_id : opportunity.id, like : like})
		.success(function(json) {
			if (typeof(json) !== "object" || typeof(json.success) !== 'boolean' || !json.success || typeof(json.opportunity) !== 'object') {
				giverhubError({msg : 'Bad response from server.'});
			} else {
				oppService.update(opportunity, json.opportunity);
			}
		})
		.error(function() {
			giverhubError({msg : 'Request Failed'});
		})
		.finally(function() {
		});

		return true;
	};

	$scope.commentText = '';
	$scope.submittingComment = false;
	$scope.submitComment = function($event, opportunity) {
		if (!$event.shiftKey) {
			$event.preventDefault();

			var text = $scope.commentText.trim();
			if (!text.length) {
				return;
			}
			$scope.submittingComment = true;
			$http.post('/volunteering_opportunity/add_comment', {'opportunity-id' : opportunity.id, text : text})
			.success(function(json) {
				if (typeof(json) !== "object" || typeof(json.success) !== 'boolean' || !json.success || typeof(json.opportunity) !== 'object') {
					giverhubError({msg : 'Bad response from server.'});
				} else {
					oppService.update(opportunity, json.opportunity);
					$scope.commentText = '';
				}
			})
			.error(function() {
				giverhubError({msg : 'Request Failed'});
			})
			.finally(function() {
				$scope.submittingComment = false;
			});
		}
	};

	$scope.share = function() {
		FB.ui({
			method: 'feed',
			link: window.location.href
		});
	};
}]);

volunteeringOpportunitiesApp.controller('VolunteeringOpportunitiesController', ['$scope', '$modal', '$http', 'oppService', 'timeZonesService', function($scope, $modal, $http, oppService, timeZonesService) {
	var $volunteering_info = jQuery('.volunteering-info');
	$scope.isNonprofitAdmin = $volunteering_info.data('is-charity-admin') ? true : false;

	oppService.addScopeForApply($scope);
	$scope.opportunities = oppService.get();
	$scope.order_by = '+next_timestamp';

	var charity_id = $volunteering_info.data('charity-id');

	$scope.open_create_modal = function () {
		var default_time_zone = timeZonesService.default_time_zone;
		$modal.open({
			templateUrl: 'create_volunteering_opportunity_modal.html',
			controller : 'CreateModalInstanceCtrl',
			windowClass : 'gh-modal-style-2 create-volunteering-opportunity-modal',
			resolve: {
				opportunity: function() {
					return {
						occurs : 'once',
						charity_id : charity_id,
						repeat : {occurrence : false},
						time_zone : default_time_zone
					};
				}
			}
		});
	};

	$scope.edit = function(opportunity) {
		$modal.open({
			templateUrl: 'create_volunteering_opportunity_modal.html',
			controller : 'CreateModalInstanceCtrl',
			windowClass : 'gh-modal-style-2 create-volunteering-opportunity-modal',
			resolve: {
				opportunity: function() {
					return angular.copy(opportunity);
				}
			}
		});
	};

	$scope.delete = function(opportunity) {
		if (confirm('Are you sure?')) {
			opportunity.deleting = true;
			$http.post('/volunteering_opportunity/delete', {'opportunity-id' : opportunity.id})
			.success(function(json) {
				if (typeof(json) !== "object" || typeof(json.success) !== 'boolean' || !json.success) {
					giverhubError({msg : 'Bad response from server.'});
				} else {
					oppService.delete(opportunity);
				}
			})
			.error(function() {
				giverhubError({msg : 'Request Failed'});
			})
			.finally(function() {
				opportunity.deleting = false;
			});
		}
	};

}]);

volunteeringOpportunitiesApp.controller('CreateModalInstanceCtrl', [
	'$scope', '$log', '$modal', '$http', '$modalInstance', 'oppService', 'opportunity',
	function($scope, $log, $modal, $http, $modalInstance, oppService, opportunity) {

		$scope.times = [
			{id : '00:00', name:'12:00AM'},
			{id : '00:30', name:'12:30AM'}
		];
		for(x = 1; x <= 11; x++) {
			$scope.times.push({id : x+':00', name:x+':00AM'});
			$scope.times.push({id : x+':30', name:x+':30AM'});
		}
		$scope.times.push({id : '12:00', name:'12:00PM'});
		$scope.times.push({id : '12:30', name:'12:30PM'});
		for(x = 1; x <= 11; x++) {
			$scope.times.push({id : x+12+':00', name:x+':00PM'});
			$scope.times.push({id : x+12+':30', name:x+':30PM'});
		}

		$scope.open_date_picker = function($event, start) {
			$event.preventDefault();
			$event.stopPropagation();

			if (start) {
				$scope.date_picker_opened_end = false;
				$scope.date_picker_opened_start = true;
			} else {
				$scope.date_picker_opened_start = false;
				$scope.date_picker_opened_end = true;
			}
		};

		$scope.today = function() {
			$scope.opportunity.start_date = new Date();
			$scope.opportunity.end_date = new Date();
		};

		$scope.opportunity = opportunity;

		if (typeof($scope.opportunity.id) === "undefined") {
			$scope.opportunity.start_time = $scope.times[7].name;
			$scope.opportunity.end_time = $scope.times[9].name;

			$scope.today();
		} else {
			$scope.opportunity.start_date = new Date(opportunity.start_date);
			$scope.opportunity.end_date = opportunity.end_date ? new Date(opportunity.end_date) : null;
		}

		$scope.saving = false;

		$scope.add = function() {
			$scope.saving = true;

			// fix the fucking dates..
			var cp = angular.copy($scope.opportunity);
			var sd = $scope.opportunity.start_date;
			var ed = $scope.opportunity.end_date;
			if (sd) {
				cp.start_date = sd.getFullYear() + '-' + (sd.getMonth()+1) + '-' + sd.getDate();
			}
			if (ed) {
				cp.end_date = ed.getFullYear() + '-' + (ed.getMonth()+1) + '-' + ed.getDate();
			}
			$http.post('/volunteering_opportunity/save', {opportunity : cp})
				.success(function(json) {
					if (typeof(json) !== "object" || typeof(json.success) !== 'boolean' || !json.success || typeof(json.opportunity) !== "object" || typeof(json.new) !== "boolean") {
						giverhubError({msg : 'Bad response from server.'});
					} else {
						if (json.new) {
							oppService.add(json.opportunity);
							jQuery('.vol-cal').each(function(i,cal) {
								var $cal = jQuery(cal);
								window.refreshOpportunityCalendar($cal);
							});
						} else {
							oppService.update($scope.opportunity, json.opportunity);
							jQuery('.vol-cal').each(function(i,cal) {
								var $cal = jQuery(cal);
								window.refreshOpportunityCalendar($cal);
							});
						}

						$modalInstance.close();
					}
				})
				.error(function() {
					giverhubError({msg : 'Request Failed'});
				})
				.finally(function() {
					$scope.saving = false;
				});
		};

		$scope.cancel = function () {
			$modalInstance.dismiss('cancel');
		};

		$scope.dateOptions = {
			formatYear: 'yy',
			startingDay: 1
		};

		$scope.date_picker_format = 'M/d/yy';

		$scope.time_zones_modal = function () {
			var modalInstance = $modal.open({
				templateUrl: 'time_zones_modal.html',
				controller : 'TimeZonesModalCtrl',
				windowClass : 'time-zones-modal gh-modal-style-2',
				resolve: {
					time_zone : function() {
						return opportunity.time_zone;
					}
				}
			});

			modalInstance.result.then(function (time_zone) {
				$scope.opportunity.time_zone = time_zone;
			}, function () {
			});
		};

	}
]);

volunteeringOpportunitiesApp.service('timeZonesService', function() {
	var $volunteering_info = jQuery('.volunteering-info');

	var _time_zones = [];
	var _default;
	jQuery.each($volunteering_info.data('time-zones'), function(id,name) {
		var time_zone = {id: id, name: name};
		_time_zones.push(time_zone);
		if (id == 'UTC-05:00') {
			_default = time_zone;
		}
	});

	return {
		time_zones : _time_zones,
		default_time_zone : null
	};
});

volunteeringOpportunitiesApp.controller('TimeZonesModalCtrl', [
	'$scope', '$modalInstance', 'timeZonesService', 'time_zone',
	function($scope, $modalInstance, timeZonesService, time_zone) {
		$scope.time_zones = timeZonesService.time_zones;

		if (time_zone !== null) {
			angular.forEach($scope.time_zones, function(t_time_zone) {
				if (t_time_zone.id == time_zone.id) {
					time_zone = t_time_zone;
				}
			});
		}

		$scope.selected = {
			time_zone : time_zone
		};

		$scope.ok = function () {
			$modalInstance.close($scope.selected.time_zone);
		};

		$scope.cancel = function () {
			$modalInstance.dismiss('cancel');
		};
	}
]);

volunteeringOpportunitiesApp.controller('ReviewVolunteeringOpportunitiesController', ['$scope', '$http', function($scope, $http) {

	var $review_volunteering_opportunities_block = jQuery('.review-volunteering-opportunities-block');

	if ($review_volunteering_opportunities_block.data('user-reviewed-already')) {
		$scope.rating = $review_volunteering_opportunities_block.data('users-rating');
		$scope.review = $review_volunteering_opportunities_block.data('users-review');
		$scope.editing = false;
	} else {
		$scope.rating = null;
		$scope.review = '';
		$scope.editing = true;
	}

	var charity_id = $review_volunteering_opportunities_block.data('charity-id');

	$scope.reviewsCount = $review_volunteering_opportunities_block.data('reviews-count');

	$scope.submitting = false;
	$scope.submit = function() {
		$scope.submitting = true;


		var data = {
			'charity-id' : charity_id,
			rating : $scope.rating,
			review : $scope.review
		};

		if (jQuery('.reviews-list-wrapper').length) {
			data['request-review-list-html'] = true;
		}

		$http.post('/volunteering_opportunity/review', data)
		.success(function(json) {
			if (typeof(json) !== "object" || typeof(json.success) !== 'boolean' || !json.success || typeof(json.reviewsCount) === "undefined") {
				giverhubError({msg : 'Bad response from server.'});
			} else {
				$scope.reviewsCount = json.reviewsCount;
				$scope.editing = false;
				if (typeof(json.reviewsListHtml) === "string") {
					jQuery('.reviews-list-wrapper').replaceWith(json.reviewsListHtml);
				}
			}
		})
		.error(function() {
			giverhubError({msg : 'Request Failed'});
		})
		.finally(function() {
			$scope.submitting = false;
		});
	}
}]);

jQuery(document).ready(function() {
	var $add_blocks = jQuery('.add-volunteering-block');
	var $volunteering_blocks = jQuery('.volunteering-opportunities-block');
	var $review_volunteering_opportunities_block = jQuery('.review-volunteering-opportunities-block');

	$add_blocks.each(function(i,e) {
		angular.bootstrap(e,['volunteeringOpportunitiesApp']);
	});
	$volunteering_blocks.each(function(i,e) {
		angular.bootstrap(e,['volunteeringOpportunitiesApp']);
	});
	$review_volunteering_opportunities_block.each(function(i,e) {
		angular.bootstrap(e,['volunteeringOpportunitiesApp']);
	});
});