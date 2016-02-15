'use strict';

angular.module('cam4tv.secondscreen')

.controller('SecondScreenController',
    ['$scope', '$rootScope', 'Pubnub', 'SecondScreenService',
    function ($scope, $rootScope, Pubnub, SecondScreenService) {
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
		        // Call firebase wrapper
		        // Extend this to send the username so that we can offer logged in chat with input
		        SecondScreenService.Chat($scope.current_view, function (response) {
			        if (response.success) {
			        	var firebase_url = response.url;
			        	
			        	var source = new EventSource(firebase_url);
						source.addEventListener('put', function(evt) {
							console.log(evt);
							var data = JSON.parse(evt.data);
							for (var key in data) {
								var obj = data[key];
								if (obj.hasOwnProperty('m')) {
									document.getElementById("chat_box").innerHTML += obj.ou+': ';
									document.getElementById("chat_box").innerHTML += obj.m+'<br/>';
								}
								if (obj.hasOwnProperty('tk')) {
									document.getElementById("chat_box").innerHTML += obj.ou+' tipped ';
									document.getElementById("chat_box").innerHTML += obj.tk+' tokens!<br/>';
								}
							}
						});
			        }
			        else {
				        document.getElementById("chat_box").innerHTML += "Firebase Error!<br>";
			        }
			    });
		    });
		});
    }]);