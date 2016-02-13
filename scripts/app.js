'use strict';

// declare modules
angular.module('cam4tv.authentication', []);
angular.module('cam4tv.home', []);
angular.module('cam4tv.secondscreen', ["pubnub.angular.service"]);

angular.module('cam4tv', [
    'cam4tv.authentication',
    'cam4tv.home',
    'cam4tv.secondscreen',
    'ngRoute',
    'ngCookies'
])

.config(['$routeProvider', '$locationProvider', function ($routeProvider, $locationProvider) {

    $routeProvider
        .when('/auth/:key_code', {
            controller: 'AuthenticationController',
            templateUrl: 'modules/authentication/views/auth.html'
        })
        
        .when('/login', {
            controller: 'AuthenticationController',
            templateUrl: 'modules/authentication/views/login.html'
        })
        
        .when('/verify', {
            controller: 'AuthenticationController',
            templateUrl: 'modules/authentication/views/verify.html'
        })
        
        .when('/confirm', {
            controller: 'AuthenticationController',
            templateUrl: 'modules/authentication/views/confirm.html'
        })
        
        .when('/chat', {
            controller: 'SecondScreenController',
            templateUrl: 'modules/secondscreen/views/chat.html'
        })

        .when('/', {
            controller: 'HomeController',
            templateUrl: 'modules/home/views/home.html'
        })

        .otherwise({ redirectTo: '/login' });
        
        $locationProvider.html5Mode(false);
}])

.run(['$rootScope', '$location', '$cookieStore', '$http',
    function ($rootScope, $location, $cookieStore, $http) {
        // keep user logged in after page refresh
        $rootScope.globals = $cookieStore.get('globals') || {};
        if ($rootScope.globals.currentUser) {
            $http.defaults.headers.common['Authorization'] = 'Basic ' + $rootScope.globals.currentUser.authdata; // jshint ignore:line
        }

        $rootScope.$on('$locationChangeStart', function (event, next, current) {
            // redirect to login page if not logged in
            if ($location.path() !== '/login' && !$rootScope.globals.currentUser) {
                $location.path('/login');
            }
        });
    }]);
