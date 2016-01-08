app.controller('HomeController',function($scope,$http,$rootScope,$cookies){
	$http({
		method: 'GET',
		url: '/apiv1/search_events/'
	})
	.then(function successCallback(response) {
		$rootScope.logged_in = response.data.logged_in;
		$scope.data = response.data.data;
		},
		function errorCallback(response) {

		}
	);
	var now = new Date();
	$cookies.put('logged_in',$rootScope.logged_in,{
		expires: new Date(now.getFullYear(), now.getMonth(), now.getDate()+1)
	});
});