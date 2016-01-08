var app = angular.module('TreasureApp',['ngAnimate','ui.router','720kb.datepicker','jkuri.timepicker','ngCookies','selectize']);

app.run(function($cookies,$rootScope){
	$rootScope.logged_in = $cookies.get('logged_in') ? $cookies.get('logged_in') : false;
	$rootScope.showServerError = function(){
		angular.element('#serverErrorModal').modal('show');
	}
});

app.config(function($stateProvider,$urlRouterProvider,$httpProvider){

	$urlRouterProvider.otherwise('/home');

	$stateProvider
		.state('home',{
			url : '/home',
			templateUrl : '/partials/home.html',
			controller: 'HomeController'
		})
		.state('edit',{
			url : '/edit',
			templateUrl : '/partials/edit.html'
		})
		.state('create-event',{
			url : '/create-event',
			templateUrl : '/partials/create-event.html',
			controller: 'CreateEventController'
		})
		.state('event',{
			url : '/event/:eventSlug',
			templateUrl : '/partials/event.html',
			controller: 'EventController'
		})
		.state('logout',{
			url : '/logout',
			controller: 'LogoutController'
		})
		.state('test',{
			url : '/test/:eventSlug/:roundNo',
			templateUrl: '/partials/test.html',
			controller: 'TestController'
		});

	$httpProvider.interceptors.push('loader');
});

app.service('loader',function($rootScope){

	this.request = function(config){
		$rootScope.loading = true;
		if(config.data)
		{
			config.data._token = $("meta[name='csrf-token']").attr('content');
		}
		return config;
	}

	this.response = function(response){
		$rootScope.loading = false;
		return response;
	}
});

/*app.directive('ngRepeatFinish',function($timeout){
    return {
        restrict: 'A',
        link: function (scope, element, attr) {
            if (scope.$last === true) {
                $timeout(function () {
                    scope.$emit('ngRepeatFinished');
                });
            }
        }
    }
})*/