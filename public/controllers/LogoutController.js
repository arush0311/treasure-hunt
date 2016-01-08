app.controller('LogoutController',function($scope,$http,$rootScope,$window,$cookies){
	$http({
		method: 'POST',
		url: '/apiv1/logout/'
	}).then(function successCallback(response) {
		$rootScope.logged_in = false;
		$cookies.remove('logged_in');
		
		$window.history.back();
	}, function errorCallback(response) {

	});

});