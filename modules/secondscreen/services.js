'use strict';

angular.module('cam4tv.secondscreen')

.factory('SecondScreenService',
    ['Base64', '$http', '$cookieStore', '$rootScope', '$timeout',
    function (Base64, $http, $cookieStore, $rootScope, $timeout) {
        var service = {};

        // Check login API, return User IDß
        // Completed: Feb. 6, 2016
        service.Chat = function (room, callback) {             
            $http.get('/cam4tv/api/chat/cam4.php?room='+room, { room: room })
               .success(function (response) {
                   callback(response);
            	});
        };

        return service;
    }
]);