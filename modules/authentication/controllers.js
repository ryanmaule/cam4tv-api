'use strict';

angular.module('cam4tv.authentication')

.controller('AuthenticationController',
    ['$scope', '$rootScope', '$location', '$routeParams', 'AuthenticationService',
    function ($scope, $rootScope, $location, $routeParams, AuthenticationService) {
        $scope.key_code = $routeParams['key_code'];
		
		// Attempt Login, if success set credentials and redirect to Verify
		// Complete: Feb 6, 2016
        $scope.login = function () {
            $scope.dataLoading = true;
            AuthenticationService.Login($scope.username, $scope.password, function (response) {
                if (response.success) {
                    AuthenticationService.SetCredentials($scope.username, $scope.password, response.user_id);
                    // Redirect to the OAuth2 verification page
                    $location.path('/verify');
                } else {
                    $scope.error = response.message;
                    $scope.dataLoading = false;
                }
            });
        };
        
        // User is Authorized.  Detect if user already has an active token.  If so, update it, if not, create it.
        // Complete: Incomplete
        $scope.auth = function () {
            $scope.dataLoading = true;
            var user_id = $rootScope.globals.currentUser.user_id;
            AuthenticationService.Auth($scope.auth_code, user_id, $routeParams.key_code, function (response) {
                if (response.success) {
	                // Setup the device
                    AuthenticationService.SetDevice($scope.auth_code, $routeParams.key_code, response.device_id);
                    //AuthenticationService.SetHistory($scope.auth_code, $routeParams.key_code, response.device_id, user_id);
                    // Redirect to the confirmation page so user can launch second screen
                    $location.path('/confirm');
                } else {
                    $scope.error = response.message;
                    $scope.dataLoading = false;
                }
            });
        };
        
        $scope.printGlobals = function () {
	        AuthenticationService.PrintGlobals();
        }
        
        $scope.logout = function () {
	        AuthenticationService.ClearCredentials();
	    };
    }]);