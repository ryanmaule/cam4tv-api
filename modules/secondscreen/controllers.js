'use strict';

angular.module('cam4tv.secondscreen')

.controller('SecondScreenController',
    ['$scope', '$rootScope', 'Pubnub',
    function ($scope, $rootScope, Pubnub) {
		Pubnub.init({
		    publish_key: 'pub-c-a9afac0f-597a-4d95-a975-83b16220f02b',
		    subscribe_key: 'sub-c-2023456c-d1a2-11e5-bcee-0619f8945a4f'
		});
		
		$scope.selectedChannel = $rootScope.globals.currentDevice.auth_code
		
		Pubnub.subscribe({
		    channel  : $scope.selectedChannel,
		    triggerEvents: ['callback']
		});
		
		$rootScope.$on(Pubnub.getMessageEventNameFor($scope.selectedChannel), function (ngEvent, message, envelope, channel) {
		    $scope.$apply(function () {
		        $scope.current_view = message
		    });
		});
    }]);